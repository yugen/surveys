<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Sirs\Surveys\Models\Survey;
use Validator;

class ResponseController extends BaseController
{
    // use DispatchesJobs, ValidatesRequests;

    public function index(Request $request, $surveySlug)
    {
        $survey = class_survey()::findBySlug($surveySlug);
        $responses = $survey->responses;
        return response()->view('surveys::responses.list', ['responses'=>$responses, 'survey'=>$survey]);
    }

    public function show($surveySlug, $id)
    {
        $response = class_survey()::findBySlug($surveySlug)->responses()->findOrFail($id);
        $surveyRoute = route('survey_get', [
        get_class($response->respondent),
        $response->respondent->id,
        $surveySlug,
        $response->id
      ]);
        return response()->view('surveys::responses.detail', ['response'=>$response, 'surveyRoute'=>$surveyRoute]);
    }
}
