<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Debugbar;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\SurveyControlService;

class SurveyController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected function setPreviousLocation(Request $request)
    {
        $previous = $request->session()->pull('survey_previous');
        if( !preg_match('/\/survey\//', URL::previous()) ){
            $previous = URL::previous();
        }
        $request->session()->put('survey_previous', $previous);
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
        $respondent = $this->getRespondent($respondentType, $respondentId);
        if ($responseId == 'new') {
            $response = $survey->getNewResponse($respondent);
            $response->save();
            return redirect(SurveyControlService::generateSurveyUrl($survey, $response));
        }else{
            $response = $survey->getLatestResponse($respondent, null, $responseId);
        }

        $control = new SurveyControlService($request, $response);

        return $control->showPage();
    }

	/**
	 * Instatiates or creates response object, validates input, checks for/runs beforeSave method from rules doc, saves input, checks for/runs afterSave method on rules doc, runs navigate function
	 *
	 * @return 
	 * @author SIRS
	 **/
    public function store(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null){

        // $this->assemblePretext($request);

        $survey = Survey::where('slug', $surveySlug)->firstOrFail();
        $survey->getSurveyDocument()->validate();
        $response = $survey->getLatestResponse($this->getRespondent($respondentType, $respondentId), null, $responseId);

        $control = new SurveyControlService($request, $response);
        return $control->saveAndContinue();
    }


    protected function getRespondent($type, $id)
    {
        $className = str_replace(' ', '\\', ucwords(str_replace('-',' ',$type)));
        return $className::findOrFail($id);
    }

    protected function assemblePretext(Request $request)
    {
        $pretext = $request->session()->get('pretext');
        $pretext = ($pretext) ? $pretext : [];
        $requestPretext = ($request->pretext) ? $request->pretext : [];
        $pretext = array_merge($pretext, $requestPretext);
        $pretext['nav'] = $request->nav;
        $request->session()->put('pretext', $pretext);
    }


}
