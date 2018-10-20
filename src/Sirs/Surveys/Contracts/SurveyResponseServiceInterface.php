<?php

namespace Sirs\Surveys\Contracts;

/**
 * Defines interface for a SurveyResponseService
 *
 * @package default
 * @author
 **/
interface SurveyResponseServiceInterface
{
    public function show(Request $request, $respondentType, $respondentId, $surveySlug, $responseId = null);

    public function store(Request $request, $respondentType, $respondentId, $surveySlug, $responseId =     null);

    public function navigate(Request $request, $respondentType, $respondentId, $surveySlug, $responseId, $pageName, $survey, $surveydoc);
} // END interface SurveyResponseServiceInterface
