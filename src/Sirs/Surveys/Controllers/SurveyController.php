<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Sirs\Surveys\Documents\SurveyDocument;
use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\Models\Response;

class SurveyController extends Controller
{


	/**
	 * Takes in a group of variables from the URL and either finds the in-progress response and displays the current page, or if no response is specified displays the first page of a given survey
	 *
	 * @return rendered page
	 * @author SIRS
	 **/
    public function show( $respondentType, $respondentId, $surveySlug, $pageName = null, $responseId = null){

    	
    	$survey = Survey::where('slug',$surveySlug)->firstOrFail(); 
    	$surveydoc = $survey->getSurveyDocument();
    	if ( is_null($responseId) ) { 
    		$surveydoc->pages[0]->render();
    	} else {
    		$response = $survey->responses()->findOrFail($responseId);
    		if ( is_null( $pageName ) ) { 
    			$pageName = $response->last_page;
    		}
			/** TO DO: Pass response into rendered survey **/
    		$pageName = $surveydoc->getPageIndexByName($pageName);
    		$surveydoc->pages[$pageName]->render(); 

    	}
    }

	/**
	 * saves input data from a survey page to SurveyResponse object, then hands off to route function
	 *
	 * @return redirect
	 * @author SIRS
	 **/
    public function save( $respondentType, $respondentId, $surveyName, $pageName, $responseId, Request $request ){
    	/** getting survey data back from the document **/
    		$data = $request->all();

    	/** TO DO: ADD VALIDATION AGAINST XML VALUES? **/

    	/** instatiate rules doc **/
    		$rulesdoc = $surveyName."Rules";
    		$rules = new $rulesdoc;
    		$method = $pageName."Save";

    	/** instatiating response **/
    		$response = SurveyResponse::firstOrFail($responseId);

    		if ( method_exists( $rules, $method ) ) {
    			// there is a custom rule for save here
    			call_user_func($rules, $method);
    		}else{
    			// there is not a custom rule for save here
    			foreach ($data as $key => $value) {
    				$response->$key = $value;
	    		}
	    		$response->save();
    		}	

    	/** getting navigation direction and passing all data on to the router, so that we don't have to re-do queries **/
    		$nav = $data['nav'];
    		$this->navigate($nav, $response, $rules, $respondentType, $respondentId, $surveyName, $pageName, $responseId);
    }


    /**
	 * checks if any custom page logic has been created, moves to next page in direction if not
	 *
	 * @return redirect
	 * @author SIRS
	 **/
    public function navigate($nav, $response, $rules, $respondentType, $respondentId, $surveyName, $pageName, $responseId){

    	/**instatiating survey document and getting pages **/
	    	$survey =  \SurveyDocument::initFromFile('directory/'.$surveyName.".xml");
	    	$pageIndex = $survey->getPageIndexByName($pageName);
	    	// get page name and find next page using the nav
	    	if ($nav = 'next') {
	    		$nav = 1;
	    	} elseif ($nav = 'prev') {
	    		$nav = -1;
	    	}
	    	$pageIndex += $nav;
	    	$target = $survey->pages[$pageIndex]->name;
	    	$method = $target."Skip";
	    /** looking for custom skip method, deciding what to do with response **/
	    	if ( method_exists( $rules, $method ) ) {
	    		$skip = call_user_func($rules, $method);
	    		switch ($skip) {
	    			case 0:
	    				// we are not skipping
	    				return redirect( action( 'SurveyController@show',[ $respondentType, $respondentId, $surveyName, $pageName, $responseId ] ) );
	    				break;
	    			case 1:
	    				// we are skipping this page
	    				$pageIndex = $survey->getPageIndexByName($target);
	    				$pageIndex += $nav;
	    				$pageName = $survey->pages[$pageIndex]->name;
	    				$this->route($nav, $response, $rules, $respondentType, $respondentId, $surveyName, $pageName, $responseId);
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
	    		$pageName = $target;
	    		return redirect( action( 'SurveyController@show',[ $respondentType, $respondentId, $surveyName, $pageName, $responseId ] ) );
	    	}
    }

}
