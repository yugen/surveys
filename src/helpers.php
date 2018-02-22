<?php

if (! function_exists('class_response')) {
    function class_response()
    {
        return config('surveys.bindings.models.Response', Sirs\Surveys\Models\Response::class);
    }
}

if (! function_exists('class_survey')) {
    function class_survey()
    {
        return config('surveys.bindings.models.Survey', Sirs\Surveys\Models\Survey::class);
    }
}

if (! function_exists('getSurveyFromUrl')) {
    function getSurveyFormUrl($response, $pageName = null)
    {
        $url = '/'.strtolower(preg_replace('/\\\/', '-', get_class($response->respondent)));
        $url .= '/'.$response->respondent->id.'/survey/'.$response->survey->slug;
        $url .= ($response->id) ? '/'.$response->id : '';
        $url .= ($pageName) ? '?page='.$pageName : '';
        return $url;
    }
}
