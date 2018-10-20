<?php
namespace Sirs\Surveys\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;

class ReportController extends BaseController
{
    public function index(Request $request)
    {
        $surveys = class_survey()::all();
        return response()->view('surveys::reports.list', ['surveys'=>$surveys]);
    }

    public function detail($surveySlug, $pageName = null, $variableName = null)
    {
        $surveyModel = class_survey()::findBySlug($surveySlug);
        $reports = $surveyModel->getReports();
        $surveyDocument = $surveyModel->getSurveyDocument();
        return response()->view('surveys::reports.detail', ['reports'=>$reports, 'survey'=>$surveyDocument, 'model' => $surveyModel, "pageName" => $pageName, "variableName" => $variableName]);
    }

    public function overview($surveySlug)
    {
        $surveyModel = class_survey()::findBySlug($surveySlug);
        $reports = $surveyModel->getReports();
        $surveyDocument = $surveyModel->getSurveyDocument();
        return response()->view('surveys::reports.overview', ['reports'=>$reports, 'survey'=>$surveyDocument, 'model' => $surveyModel]);
    }
}
