<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
// use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
// use Illuminate\Support\Facades\Log;
// use Sirs\Surveys\Documents\SurveyDocument;
// use Sirs\Surveys\Exceptions\InvalidSurveyResponseException;
// use Sirs\Surveys\Exceptions\SurveyNavigationException;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;
use Validator;

class ResponseController extends BaseController
{
    // use DispatchesJobs, ValidatesRequests;

    public function index(Request $request, $surveySlug)
    {
      $survey = Survey::findBySlug($surveySlug);
      $responses = $survey->responses;
      return response()->view('surveys::responses.list', ['responses'=>$responses, 'survey'=>$survey]);
    }

    public function show($surveySlug, $id)
    {
      $response = Survey::findBySlug($surveySlug)->responses()->findOrFail($id);
      $surveyRoute = route('survey_get', [
        get_class($response->respondent), 
        $response->respondent->id, 
        $surveySlug,
        $response->id 
      ]);
      return response()->view('surveys::responses.detail', ['response'=>$response, 'surveyRoute'=>$surveyRoute]);
    }
}