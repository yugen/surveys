<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;
use Validator;

class DictionaryController extends BaseController
{
    // use DispatchesJobs, ValidatesRequests;

    public function index(Request $request)
    {
      $surveys = Survey::all();
      return response()->view('surveys::dictionary.list', ['surveys'=>$surveys]);
    }

    public function show($surveySlug)
    {
      $survey = Survey::findBySlug($surveySlug);
      return response()->view('surveys::dictionary.detail', ['survey'=>$survey]);
    }

}