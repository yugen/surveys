<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Sirs\Surveys\Documents\SurveyDocument;
use Sirs\Surveys\Exceptions\InvalidSurveyResponseException;
use Sirs\Surveys\Exceptions\SurveyNavigationException;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;
use Validator;

class SurveyController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

	/**
	 * Takes in a group of variables from the URL and either finds the in-progress response and displays the current page, or if no response is specified displays the first page of a given survey
	 *
	 * @return rendered page
	 * @author SIRS
	 **/
    public function show(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null){
    	$survey = Survey::where('slug',$surveySlug)->firstOrFail();
        $respondent = $this->getRespondent($respondentType, $respondentId);
        $response = $survey->getLatestResponse(get_class($respondent), $respondentId, $responseId);
        $page = $survey->getSurveyDocument()->getPage($request->input('page'));
        $rules = $survey->getRules($response);
        if( ctype_digit($request->input('page')) ){
            $pageIdx = (int)$page-1;
        }else{
            $pageIdx = $survey->getSurveyDocument()->getPageIndexByName($request->input('page'));
        }

        $context = [
            'survey'=>[
                'name'=>$survey->name,
                'version'=>$survey->version,
                'totalPages'=>count($survey->getSurveyDocument()->pages),
                'currentPageIdx'=>$pageIdx
            ],
            'respondent'=>$respondent,
            'response'=>$response
        ];
        // dd($context);

        if( $ruleContext = $this->execRule($rules, $page->name, 'BeforeShow') ){
            $context = array_merge($context, $ruleContext);
        }

        return  $page->render($context); 
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

        $surveydoc = $survey->getSurveyDocument();
        $page = $surveydoc->getPage($request->input('page'));
    	// validating data
    	$validation = $page->getValidation();
    	$validator = Validator::make( $request->all(), $validation);
    	if ( $validator->fails() ) {
               throw new InvalidSurveyResponseException($validator->errors());
    		/** TO DO: Return rendered page with errors  */
    	}

    	// get the rules object
    	$rules = $survey->getRules($response);

        // run the after save rule for the page (if any).
        $this->execRule($rules, $page->name, 'BeforeSave');

    	// saving data
    	foreach ($data as $key => $value) {
    		$response->$key = $value;
    	}
        $response->survey_id = $survey->id;
        $response->respondent_type = get_class($respondent);
        $response->respondent_id = $respondent->id;
    	$response->save();

        // run the after save rule for the page (if any).
        $this->execRule($rules, $page->name, 'AfterSave');

    	if ( $request->input('nav') == 'finalize' ) {
    		$response->finalize();
    	}
        if( $request->input('nav') ){
            $redirectUrl = $respondentType.'/'.$respondentId.'/survey/'.$surveySlug.'/'.$responseId.'?page='.$page->name;
            return redirect($redirectUrl);
        }

    	// passing all data to navigate function
    	return $this->navigate($request, $respondentType, $respondentId, $surveySlug, $response->id, $page->name, $survey, $surveydoc);
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
    				$response->finalize();
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

    protected function execRule($rulesObj, $pageName, $methodName)
    {
       $method = $pageName . $methodName;
        if ( method_exists( $rulesObj, $method ) ) {
            return $rulesObj->$method();
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
