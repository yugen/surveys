<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;
use Validator;

class ReportController extends BaseController
{
    // use DispatchesJobs, ValidatesRequests;

    public function index(Request $request, $surveySlug)
    {
      
    }

    public function detail($surveySlug, $pageName = null, $variableName = null)
    {
      $surveyModel = Survey::findBySlug($surveySlug);
      $reports = $surveyModel->getReports();
      $surveyDocument = $surveyModel->getSurveyDocument();
      return response()->view('surveys::reports.detail', ['reports'=>$reports, 'survey'=>$surveyDocument, 'model' => $surveyModel, "pageName" => $pageName, "variableName" => $variableName]);
    }

    public function overview($surveySlug){
      $surveyModel = Survey::findBySlug($surveySlug);
      $reports = $surveyModel->getReports();
      $surveyDocument = $surveyModel->getSurveyDocument();
      return response()->view('surveys::reports.overview', ['reports'=>$reports, 'survey'=>$surveyDocument, 'model' => $surveyModel]);
    }
}