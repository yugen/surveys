<?php
namespace Sirs\Surveys\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DictionaryController extends BaseController
{

    public function index(Request $request)
    {
      $surveys = class_survey()::all();
      return response()->view('surveys::dictionary.list', ['surveys'=>$surveys]);
    }

    public function show($surveySlug)
    {
      $survey = class_survey()::findBySlug($surveySlug);
      return response()->view('surveys::dictionary.detail', ['survey'=>$survey]);
    }

}