<?php

namespace Sirs\Surveys\Controllers;

use Auth;
use Debugbar;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Sirs\Surveys\Contracts\Survey;
use Sirs\Surveys\SurveyControlService;

class SurveyController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected function setPreviousLocation(Request $request)
    {
        $previous = $request->session()->pull('survey_previous');
        if (!preg_match('/\/survey\//', URL::previous())) {
            $previous = URL::previous();
        }
        $request->session()->put('survey_previous', $previous);
    }

    /**
     * Takes in a group of variables from the URL and finds the in-progress response and displays the current page,
     * or if no response is specified displays the first page of a given survey
     *
     * @return rendered page
     * @author SIRS
     **/
    public function show(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null)
    {
        $this->setPreviousLocation($request);

        $survey = class_survey()::where('slug', $surveySlug)->firstOrFail();
        $respondent = $this->getRespondent($respondentType, $respondentId);
        if ($responseId == 'new') {
            $response = $survey->getNewResponse($respondent);
            $response->save();
            return redirect(SurveyControlService::generateSurveyUrl($survey, $response));
        } else {
            $response = $survey->getLatestResponse($respondent, $responseId);
        }

        $control = new SurveyControlService($request, $response);

        return $control->showPage();
    }

    /**
     * Instatiates or creates response object, validates input,
     * checks for/runs beforeSave method from rules doc, saves
     * input, checks for/runs afterSave method on rules doc, runs navigate function
     *
     * @return
     * @author SIRS
     **/
    public function store(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null)
    {

        // $this->assemblePretext($request);

        $survey = class_survey()::where('slug', $surveySlug)->firstOrFail();
        $survey->getSurveyDocument()->validate();
        $response = $survey->getLatestResponse($this->getRespondent($respondentType, $respondentId), $responseId);

        $control = new SurveyControlService($request, $response);
        return $control->saveAndContinue();
    }

    public function autosave(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null)
    {
        $survey = class_survey()::where('slug', $surveySlug)->firstOrFail();
        $survey->getSurveyDocument()->validate();
        $response = $survey->getLatestResponse($this->getRespondent($respondentType, $respondentId), $responseId);
        $response->setTable($survey->response_table);

        $request->merge(['nav' => 'autosave']);

        $svc = new SurveyControlService($request, $response);
        if ($errors = $svc->getValidationErrors()) {
            $log = "Auto-Save Validation Errors\nSurvey Name: {$survey->name}\nResponse ID: {$responseId}\nValidation Errors:";
            foreach ($errors->toArray() as $column => $error) {
                $log .= "\n{$column} - {$error[0]}";
            }
            \Log::warning($log);
        } else {
            $svc->storeResponseData();
        }

        return $svc->response->toJson();
    }

    protected function getRespondent($type, $id)
    {
        $className = str_replace(' ', '\\', ucwords(str_replace('-', ' ', $type)));
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
