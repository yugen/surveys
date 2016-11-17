<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Sirs\Surveys\Documents\SurveyDocument;
use Sirs\Surveys\Exceptions\InvalidSurveyResponseException;
use Sirs\Surveys\Exceptions\SurveyNavigationException;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;
use Validator;

class SurveyController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;


    protected function render($survey, $page, $pageIdx, $response, $respondent, $errors=null){
        $rules = $survey->getRules($response);
        $context = [
            'survey'=>[
                'name'=>$survey->name,
                'title'=>$survey->getSurveyDocument()->title,
                'version'=>$survey->version,
                'totalPages'=>count($survey->getSurveyDocument()->pages),
                'currentPageIdx'=>$pageIdx
            ],
            'respondent'=>$respondent,
            'response'=>$response
        ];
        if($errors){
            $context['errors'] = $errors;   
        }

        if( $ruleContext = $this->execRule($rules, $page->name, 'beforeShow') ){
            $context = array_merge($context, $ruleContext);
        }

        return  $page->render($context); 
    }

    protected function setPreviousLocation(Request $request)
    {
        $previous = $request->session()->pull('survey_previous');
        if( !preg_match('/\/survey\//', URL::previous()) ){
            $previous = URL::previous();
        }
        $request->session()->put('survey_previous', $previous);
    }

    protected function resolveCurrentPage($request, $response)
    {
        if ($request->page) {
            return $request->page;
        }elseif($response->last_page){
            return $response->last_page;
        }else{
            return null;
        }
    }

    protected function redirect(Request $request, $rules)
    {
        // get the redirect url
        $redirectUrl = null;
        if( method_exists($rules, 'getRedirectUrl') ){
            $redirectUrl = $rules->getRedirectUrl();
        }
        if(!$redirectUrl){
            $redirectUrl = $request->session()->pull('survey_previous', '/');
            $request->session()->forget('survey_previous');
        }
        return redirect($redirectUrl);
    }

	/**
	 * Takes in a group of variables from the URL and either finds the in-progress response and displays the current page, or if no response is specified displays the first page of a given survey
	 *
	 * @return rendered page
	 * @author SIRS
	 **/
    public function show(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null){

        $this->setPreviousLocation($request);

    	$survey = Survey::where('slug',$surveySlug)->firstOrFail();
        
        $survey->getSurveyDocument()->validate();

        $respondent = $this->getRespondent($respondentType, $respondentId);
        switch ( $responseId ) {
            case 'new':
                $response = $survey->getNewResponse( get_class($respondent), $respondentId);
                $response->save();
                return redirect($respondentType."/".$respondentId."/survey/".$surveySlug."/".$response->id);
                break;
            case 'latest':
            default:
                $response = $survey->getLatestResponse(get_class($respondent), $respondentId, $responseId);
                break;
        }
        
        $page = $survey->getSurveyDocument()->getPage($this->resolveCurrentPage($request, $response));
        $response->update(['last_page'=>$page->name]);

        if( $response->finalized_at ){
            // return 'already finalized';
            return redirect()->route('surveys.{surveySlug}.responses.show', [$survey->slug, $responseId]);
        }

        if( ctype_digit($request->input('page')) ){
            $pageIdx = (int)$page-1;
        }else{
            $pageIdx = $survey->getSurveyDocument()->getPageIndexByName($page->name);
        }

        return $this->render($survey, $page, $pageIdx, $response, $respondent);
    }

	/**
	 * Instatiates or creates response object, validates input, checks for/runs beforeSave method from rules doc, saves input, checks for/runs afterSave method on rules doc, runs navigate function
	 *
	 * @return 
	 * @author SIRS
	 **/
    public function store(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null){


    	// instatiating objects
    	$data = $request->except(['_token', 'nav', 'page']);
        $respondent = $this->getRespondent($respondentType, $respondentId);
    	$survey = Survey::where('slug', $surveySlug)->firstOrFail();

        $response = $survey->getLatestResponse($respondentType, $respondentId, $responseId);
        if( !$response ){
            $response = Response::newResponse($survey->response_table);
        }


        // get the rules object
        $rules = $survey->getRules($response);

        $surveydoc = $survey->getSurveyDocument();
        $page = $surveydoc->getPage($this->resolveCurrentPage($request, $response));

        $pageVariables = collect($page->getVariables())->keyBy('name');

        $response->last_page = $page->name;

        if( ctype_digit($request->input('page')) ){
            $pageIdx = (int)$page-1;
        }else{
            $pageIdx = $survey->getSurveyDocument()->getPageIndexByName($page->name);
        }

        foreach ($data as $key => $value) {
            if( in_array($key, $pageVariables->keys()->all()) ){
                $response->$key = ($value == '') ? null : $value;
            }
        }

        $dataKeys = array_keys($data);
        foreach( $pageVariables as $idx => $pageVar ){
            if(!in_array($pageVar->name, $dataKeys) ){
                $response->{$pageVar->name} = null;
            }
        }

        $response->survey_id = $survey->id;
        $response->respondent_type = get_class($respondent);
        $response->respondent_id = $respondent->id;

    	// validating data
    	$validation = $page->getValidation();
    	$validator = Validator::make( $request->all(), $validation);
        $augmentedValidator = $this->execRule($rules, $page->name, 'GetValidator', ['validator'=>$validator]);
        $validator = ($augmentedValidator) ? $augmentedValidator : $validator;

    	if ( in_array($request->input('nav'), ['next', 'finalize']) && $validator->fails() ) {
               // throw new InvalidSurveyResponseException($validator->errors());
            return $this->render($survey, $page, $pageIdx, $response, $respondent, $validator->errors());
    		/** TO DO: Return rendered page with errors  */
    	}


    	// saving data

        // run the after save rule for the page (if any).
        $this->execRule($rules, $page->name, 'BeforeSave');

    	$response->save();

        // run the after save rule for the page (if any).
        $this->execRule($rules, $page->name, 'AfterSave');

        // we have a destination_page specified in the request go there.

        switch ($request->input('nav')) {
            case 'finalize':
                $response->finalize();
                $response = $this->redirect($request, $rules);
                break;
            case 'save':
                if ($request->destination_page) {
                    $redirectUrl = $respondentType.'/'.$respondentId.'/survey/'.$surveySlug.'/'.$responseId.'?page='.$request->destination_page;
                }else{
                    $redirectUrl = $respondentType.'/'.$respondentId.'/survey/'.$surveySlug.'/'.$responseId.'?page='.$page->name;
                }
                $response = redirect($redirectUrl);
                break;
            default:
                // passing all data to navigate function
                $response = $this->navigate($request, $respondentType, $respondentId, $surveySlug, $response->id, $page->name, $survey, $surveydoc);
                break;
        }

        return $response;
    }


    /**
	 * checks if any custom page logic has been created, moves to next page in direction if not
	 *
	 * @return redirect
	 * @author SIRS
	 **/
    public function navigate(Request $request, $respondentType, $respondentId, $surveySlug, $responseId, $pageName, $survey, $surveydoc){

		// getting page index and incrementing it to match the navigation button
    	if ($request->input('nav') == 'next') {
    		$pageIndex = $surveydoc->getPageIndexByName($pageName) + 1;
    	} elseif ($request->input('nav') == 'prev') {
    		$pageIndex = $surveydoc->getPageIndexByName($pageName) - 1;
    	}else{
            throw new SurveyNavigationException($request->input('nav'));
        }

        $response = $survey->getLatestResponse($respondentType, $respondentId, $responseId);
    	$target = $surveydoc->pages[$pageIndex]->name;
    	$rules = $survey->getRules($response);
        $skip = $this->execRule($rules, $target, 'Skip');

    	// looking for custom skip method, deciding what to do with response
    	if ( $skip ) {
    		switch ($skip) {

    			case 0:
    				// we are not skipping page
    				$pageName = $target;
    				return redirect( action( 'SurveyController@show',[ $respondentType, $respondentId, $surveySlug, $responseId, $pageName ] ) );
    				break;

    			case 1:
    				// we are skipping this page
    				$pageIndex = $survey->getPageIndexByName($target);
    				$pageIndex += $nav;
    				$pageName = $survey->pages[$pageIndex]->name;
    				$this->navigate($request, $respondentType, $respondentId, $surveySlug, $responseId, $pageName, $data, $survey, $surveydoc);
    				break;

    			case 2:
    				// we are finalizing
    				try{
                        $response->finalize();
                    }catch(ResponsePreviouslyFinalizedException $e){
                        Log::notice($e->getMessage());
                    }
                    return $this->redirect($request, $rules); //redirect to 
    				break;

                case 3:
                    return $this->redirect($request, $rules); //redirect to 
                    break;
                    
    			default:
    				Throw new InvalidInputException("Invalid value returned in ".$target);
    				break;
    		}

    	} else{
    		// no custom method
            $redirectUrl = $respondentType.'/'.$respondentId.'/survey/'.$surveySlug.'/'.$responseId.'?page='.$target;
            return redirect($redirectUrl);
    	}
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

    protected function getRespondent($type, $id)
    {
        $className = str_replace(' ', '\\', ucwords(str_replace('-',' ',$type)));
        return $className::findOrFail($id);
    }



}
