<?php
namespace Sirs\Surveys\Contracts;

use Carbon\Carbon;

interface SurveyResponse 
{

    /**
     * allows you to statically intialize Response
     * @param string $surveyName ame of survey
     * @param string  $versionNumber Version of the survey
     *
     * @return void
     */
    public static function lookupTable($table);

    public static function newResponse($table);

    /**
     * override getTable to return correct table if not set.
     *
     * @return string
     **/
    public function getTable();
    
    /**
     * finalize survey if it has not already been finalized
     * @param string $finalizeDate date string of time to set finalized_at to
     * @param bool  $override allow setting a new finalized_at date even if one already exists
     *
     * @return void
     * @example
     *    $responses  = Response;
     *    $responses->setSurveyVersion('Baseline')->get();
     *
     *    $response = Response::surveyVersion('Baseline', 3)->findOrFail(4);
     */
    public function finalizeResponse(Carbon $finalizeDate = null, $override = false);

    public function finalize(Carbon $finalizedDate = null, $override = false);

    public function survey();

    public function respondent();

    public function getDataAttributes();

    /**
     * Sets the values of the dataAttributes from an associative array
     *
     * @return void
     * @param Array $data Associative array of data field=>value
     **/
    public function setDataValues($data, $page);

    /**
     * Gets the field names for data attributes
     *
     * @return void
     * @author
     **/
    public function getDataAttributeNames();
}
