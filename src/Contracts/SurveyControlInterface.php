<?php 

namespace Sirs\Surveys\Contracts;

/**
 * describes public api of Survey Control Service
 *
 * @package sirs/survesy
 * @author
 **/
interface SurveyControlInterface
{
    public function __construct(Request $request, SurveyResponse $response);

    public function resolveCurrentPageName();

    /**
     * return the rendered $this->page or the redirection to the data view screen.
     *
     * @return string
     * @author
     **/
    public function showPage();

    /**
     * Validate and store the response data
     *
     * @return Illuminate\Http\Response
     */
    public function storeResponseData();

    public function followNav();

    public function saveAndContinue();

    public function navigate();

    public function getValidationErrors();

    public function shouldValidate();

    public function buildBaseContext($errors = null);
} // END interface SurveyControlInterface
