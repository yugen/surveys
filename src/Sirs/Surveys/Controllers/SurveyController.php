<?php
namespace Sirs\Surveys\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Sirs\Surveys\Documents\SurveyDocument;
use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\Models\Response;
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
    public function show( $respondentType, $respondentId, $surveySlug, $responseId = null, $pageName = null){

    	
    	$survey = Survey::where('slug',$surveySlug)->firstOrFail(); 
    	$surveydoc = $survey->getSurveyDocument();
    	if ( is_null($responseId) ) { 
    		$view = $surveydoc->pages[0]->render();
            return $view;
    	} else {
    		$response = $survey->responses()->findOrFail($responseId);
    		if ( is_null( $pageName ) ) { 
    			$pageName = $response->last_page;
    		}
            
			/** TO DO: Pass response into rendered survey **/

    		$pageName = $surveydoc->getPageIndexByName($pageName);
            $rules = $surveySlug . "Rules";
            $beforeShow = $pageName . "BeforeShow";
            if ( method_exists( $rules, $beforeShow ) ) {
                call_user_func($rules, $beforeShow);
            }
    		$surveydoc->pages[$pageName]->render(); 

    	}
    }

	/**
	 * Instatiates or creates response object, validates input, checks for/runs beforeSave method from rules doc, saves input, checks for/runs afterSave method on rules doc, runs navigate function
	 *
	 * @return 
	 * @author SIRS
	 **/
    public function store( $respondentType, $respondentId, $surveySlug, $responseId = null, $pageName, Request $request ){
    	
    	// instatiating objects
    	$data = $request->all();
    	$survey = Survey::where('slug', $surveySlug)->firstOrFail();
    	$surveydoc = $survey->getSurveyDocument();
    	if ( is_null( $responseId ) ) {
    		$response = $survey->responses();
    	} else {
    		$response = $survey->responses()->findOrFail($responseId);
    	}

    	// validating data
    	$validation = $surveydoc->pages[ $surveydoc->getPageIndexByName( $pageName ) ]->getValidation();
    	$validator = Validator::make( $request->all(), $validation);
    	if ( $validator->fails() ) {
    		/** TO DO: Return rendered page with errors  */
    	}

    	// creating strings for the rules doc
    	$rules = $surveySlug . "Rules";
    	$beforeSave = $pageName . "BeforeSave";
    	$afterSave = $pageName . "AfterSave";

    	// checking for and running before save method in rules doc
    	if ( method_exists( $rules, $beforeSave ) ) {
    		call_user_func($rules, $beforeSave);
    	}

    	// saving data
    	foreach ($data as $key => $value) {
    		$response->$key = $value;
    	}
    	$response->save();
    	$responseId = $response->id;

    	// checking for and running after save method in rules doc
    	if ( method_exists( $rules, $afterSave ) ) {
    		call_user_func($rules, $afterSave);
    	}

    	if ( $data['nav'] == 'finalize' ) {
    		$response->finalize();
    	}

    	// passing all data to navigate function
    	$this->navigate($respondentType, $respondentId, $surveySlug, $responseId, $pageName, $data, $survey, $surveydoc);
    }


    /**
	 * checks if any custom page logic has been created, moves to next page in direction if not
	 *
	 * @return redirect
	 * @author SIRS
	 **/
    public function navigate($respondentType, $respondentId, $surveySlug, $responseId, $pageName, $data, $survey, $surveydoc){

		// getting page index and incrementing it to match the navigation button
    	$origin = $surveydoc->getPageIndexByName($pageName);
    	if ($data['nav'] = 'next') {
    		$nav = 1;
    	} elseif ($data['nav'] = 'prev') {
    		$nav = -1;
    	}
    	$pageIndex  = $origin + $nav;
    	$target = $survey->pages[$pageIndex]->name;
    	$rules = $surveySlug . "Rules";
    	$method = $target."Skip";

    	// looking for custom skip method, deciding what to do with response
    	if ( method_exists( $rules, $method ) ) {
    		$skip = call_user_func($rules, $method);

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
    				$this->navigate($respondentType, $respondentId, $surveySlug, $responseId, $pageName, $data, $survey, $surveydoc);
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
    		return redirect( action( 'SurveyController@show',[ $respondentType, $respondentId, $surveySlug, $responseId, $pageName ] ) );
    	}
    }

}
