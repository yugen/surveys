<?php

if (!function_exists('class_response')) {
    function class_response()
    {
        return config('surveys.bindings.models.Response', Sirs\Surveys\Models\Response::class);
    }
}

if (!function_exists('class_survey')) {
    function class_survey()
    {
        return config('surveys.bindings.models.Survey', Sirs\Surveys\Models\Survey::class);
    }
}

if (!function_exists('getSurveyFromUrl')) {
    function getSurveyFormUrl($response, $pageName = null)
    {
        $url = '/'.strtolower(preg_replace('/\\\/', '-', get_class($response->respondent)));
        $url .= '/'.$response->respondent->id.'/survey/'.$response->survey->slug;
        $url .= ($pageName) ? '?page='.$pageName : '';

        return $url;
    }
}

if (!function_exists('getSurveyValidationMessages')) {
    function getSurveyValidationMessages()
    {
        $defaultOverrides = [
            'required' => 'This field is required',
            'required_if' => 'This field is required',
            'required_unless' => 'This field is required',
            'required_with' => 'This field is required',
            'required_without' => 'This field is required',
            'required_with_all' => 'This field is required',
            'required_without_all' => 'This field is required',
        ];

        return array_merge($defaultOverrides, config('surveys.validation_messages', []));
    }
}

/**
 * Why on earth did I put this in a helper function instead of the 
 * single place I used it (Response.decodeJsonField)?  Seriously, one use.
 * 
 * Going to leave this here for now b/c I'm afraid of unforseen consequences of moving it.
 */
if (!function_exists('decodeJson')) {
    function decodeJson($jsonString, $assoc = false, $depth = 512)
    {
        if (!is_string($jsonString)) {
            throw new InvalidArgumentException('decodeJson expectes first argument to be a string.  '.gettype($jsonString).' given');
        }
        return json_decode($jsonString, $assoc, $depth, JSON_THROW_ON_ERROR);
    }
}
