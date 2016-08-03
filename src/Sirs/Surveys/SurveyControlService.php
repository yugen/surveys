<?php

namespace Sirs\Surveys;

use Illuminate\Http\Request;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;

/**
 * Class defines the default SurveyControlService
 *
 * @package sirs/surveys
 * @author 
 **/
class SurveyControlService
{
    protected $request;
    protected $survey;
    protected $response;
    protected $rules;
    protected $page;

    /**
     * constructor
     *
     * @param  Illuminate\Http\Request $request request 
     * @return void
     * @author 
     **/
    public function __construct(Request $request, Survey $survey, Response $response)
    {
        $this->request = $request;
        $this->survey = $survey;
        $this->response = $response;
        if ($request->getMethod() == 'POST') {
            $this->response->setDataValues($request->all());
        }
        $this->rules = $this->survey->getRules($response);
        $this->rules->setPretext($this->request);
        $this->page = $this->survey->getSurveyDocument()->getPage($this->request->input('page'));
    }
} // END class SurveyControlService