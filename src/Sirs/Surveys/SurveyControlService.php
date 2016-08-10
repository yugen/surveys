<?php

namespace Sirs\Surveys;

use Debugbar as Dbg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sirs\Surveys\Exceptions\InvalidSurveyResponseException;
use Sirs\Surveys\Exceptions\SurveyNavigationException;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;

/**
 * Class defines the default SurveyControlService
 *
 * @package sirs/surveys
 * @author 
 **/
class SurveyControlService
{
    protected $request;
    protected $survey;
    protected $response;
    protected $rules;
    protected $page;

    /**
     * constructor
     *
     * @param  Illuminate\Http\Request $request request 
     * @return void
     * @author 
     **/
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->survey = $response->survey;
        
        $this->rules = $this->survey->getRules($response);
        $this->rules->setPretext($request);

        $this->page = $this->survey->getSurveyDocument()->getPage($request->input('page'));

        // parse the request into various parts.
        if ($request->getMethod() == 'POST') {
            $this->response->setDataValues($request->all(), $this->page);
        }
    }

    /**
     * return the rendered $this->page or the redirection to the data view screen.
     *
     * @return string
     * @author 
     **/
    public function showPage()
    {
        if( $this->response->finalized_at ){
            // return 'already finalized';
            return redirect()->route('surveys.{surveySlug}.responses.show', [$this->survey->slug, $responseId]);
        }
        return $this->render();
    }

    /**
     * Validate and store the response data
     * 
     * @return Illuminate\Http\Response
     */
    public function storeResponseData()
    {
        // run the after save rule for the page (if any).
        $this->execRule($this->rules, $this->page->name, 'BeforeSave');

        $this->response->save();

        // run the after save rule for the page (if any).
        $this->execRule($this->rules, $this->page->name, 'AfterSave');
    }

    public function followNav()
    {
        switch ($this->request->input('nav')) {
            case 'finalize':
                $this->response->finalize();
            case 'save_exit':
                $httpResponse = $this->redirect();
                break;
            case 'save':
                $destinationPage = ($this->request->destination_page) ? $this->request->destination_page : $this->page->name;
                $httpResponse = redirect($this->survey_url.'?page='.$destinationPage);
                break;
            default:
                // passing all data to navigate function
                $httpResponse = $this->navigate();
                break;
        }

        return $httpResponse;        
    }

    public function saveAndContinue()
    {
        if ($errors = $this->getValidationErrors()) {
            return $this->render($errors);
        }else{
            $this->storeResponseData();
            return $this->followNav();
        }
    }

    public function navigate()
    {
        // getting page index and incrementing it to match the navigation button
        if ($this->request->input('nav') == 'next') {
            $pageNumber = $this->page->pageNumber + 1;
        } elseif ($this->request->input('nav') == 'prev') {
            $pageNumber = $this->page->pageNumber - 1;
        }else{
            throw new SurveyNavigationException($this->request->input('nav'));
        }

        $target = $this->survey->survey_document->pages[($pageNumber - 1)];

        $skip = $this->execRule($this->rules, $target->name, 'Skip');

        if ( $skip ) {
            switch ($skip) {
                case 0: // we are not skipping page
                    $params = [ 
                        $this->response->respondent_type, 
                        $this->response->respondent_id, 
                        $this->survey-slug, 
                        $this->response->id, 
                        $target->name 
                    ];
                    return redirect( action( 'SurveyController@show', $params ) );
                    break;

                case 1: // we are skipping this page
                    $this->page = $target;
                    $httpResponse = $this->navigate();
                    break;

                case 2: // we are finalizing
                    try{
                        $response->finalize();
                    }catch(ResponsePreviouslyFinalizedException $e){
                        Log::notice($e->getMessage());
                    }
                    $httpResponse = $this->redirect(); //redirect to 
                    // $request->session()->forget('pretext');
                    break;

                case 3: // 
                    $httpResponse = $this->redirect(); //redirect to 
                    // $request->session()->forget('pretext');
                    break;
                    
                default:
                    Throw new InvalidInputException("Invalid value returned in ".$target);
                    break;
            }

        } else{
            // no custom method
            $httpResponse = redirect($this->survey_url.'?page='.$target->name);
        }
        return $httpResponse;
    }

    protected function getSurveyUrlAttribute()
    {
        $urlParts = [
            strtolower(preg_replace('/\\\/', '-',$this->response->respondent_type)),
            $this->response->respondent_id,
            'survey',
            $this->survey->slug,
            $this->response->id
        ];
        return implode('/', $urlParts);
    }


    protected function redirect()
    {
        // get the redirect url
        $redirectUrl = null;
        if( method_exists($this->rules, 'getRedirectUrl') ){
            $redirectUrl = $this->rules->getRedirectUrl();
        }
        if(!$redirectUrl){
            $redirectUrl = $this->request->session()->pull('survey_previous', '/');
        }
        $this->rules->forgetPretext();
        return redirect($redirectUrl);
    }

    public function getValidationErrors()
    {
        // Validate
        // dd($this->page->getValidation());
        $validator = Validator::make( $this->request->all(), $this->page->getValidation());
        $augmentedValidator = $this->execRule($this->rules, $this->page->name, 'GetValidator', ['validator'=>$validator]);
        $validator = ($augmentedValidator) ? $augmentedValidator : $validator;
        
        if ( $validator->fails() && in_array($this->request->input('nav'), ['next', 'finalize']) ) {
            return $validator->errors();
        }
        return null;
    }

    /**
     * Render the $this->page for $this->response
     * 
     * @param  mixed $errors error bag from validation
     * @return string         rendered page
     */
    protected function render($errors = null)
    {
        $context = $this->buildBaseContext($errors);
        if( $ruleContext = $this->execRule($this->rules, $this->page->name, 'beforeShow') ){
            $context = array_merge($context, $ruleContext);
        }
        return  $this->page->render($context); 
    }

    public function buildBaseContext($errors = null)
    {
        $context = [
            'survey'=>[
                'name'=>$this->survey->name,
                'title'=>$this->survey->title,
                'version'=>$this->survey->version,
                'totalPages'=>count($this->survey->pages),
                'currentPageIdx'=> $this->page->pageNumber - 1
            ],
            'respondent'=>$this->response->respondent,
            'response'=>$this->response,
            'pretext'=>$this->rules->pretext
        ];

        if($errors){
            $context['errors'] = $errors;   
        }
        return $context;
    }


    protected function execRule($rulesObj, $pageName, $methodName, $params = null)
    {
        $pageMethod = $pageName . ucfirst($methodName);
        $method = lcfirst($methodName);

        if ( method_exists( $rulesObj, $pageMethod ) ) {
            // run the page-specific method if we find it.
            return ( $params ) 
                        ? $rulesObj->$pageMethod($params) 
                        :  $rulesObj->$pageMethod();
        }elseif( method_exists( $rulesObj, $method)){
            // run the survey-wide method if we find it.
            return ( $params ) 
                        ? $rulesObj->$method($params) 
                        : $rulesObj->$method();
        }else{
            return;
        }
    }

    /**
     * Aliases accessors  and mutators
     *
     * @return mixed
     **/
    public function __get($attr)
    {
        if (isset($this->{$attr})) {
            return $this->{$attr};
        }elseif(method_exists($this, 'get'.camel_case($attr).'Attribute')){
            $methodName = 'get'.camel_case($attr).'Attribute';
            return $this->$methodName();
        }
    }

} // END class SurveyControlService