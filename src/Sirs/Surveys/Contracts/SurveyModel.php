<?php
namespace Sirs\Surveys\Contracts;

use Sirs\Surveys\Models\Response;

/**
 * Contract defining public API for a Survey model
 *
 * @package default
 * @author 
 **/
interface SurveyModel
{
    /**
     * Scope for name
     *
     * @return Query Builder Object
     * @author SIRS
     **/
    public function scopeName($query, $name);

    /**
     * scope for Version
     *
     * @return Query Builder Object
     * @author SIRS
     **/
    public function scopeVersion($query, $version);

    /**
     * scope for slug
     *
     * @return Query Builder Object
     * @author SIRS
     **/
    public function scopeSlug($query, $slug);

    public function getDocumentAttribute();

    /**
     * get response object for a given survey
     *
     * @return Response object(s)
     * @author SIRS
     **/
    public function responses();

    /**
     * get all responses for a given survey
     *
     * @return Response object(s)
     * @author SIRS
     **/
    public function getResponsesAttribute();

    public function getNameVersionAttribute();

    /**
     * Gets the validation rules for a given survey in a format acceptable for laravel validation
     *
     * @return array
     * @author SIRS
     **/
    public function getValidationRules();

    public function getLatestResponse($respondent, $responseId = null);

    public function getNewResponse($respondent);

    public function getPagesAttribute();

    public function getRules(Response $response);


    /**
     * returns an array of questions
     *
     * @return array
     * @author SIRS
     **/
    public function getQuestions();

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function getReports();

} // END interface SurveyContract