<?php

$routeGroup = (config('surveys.routeGroup')) ? config('surveys.routeGroup') : []; 
Route::group($routeGroup, function(){
  Route::get('{respondentId}/{respondentType}/survey/{surveySlug}/{responseId?}/{pageName?}', [
    'uses' => 'Sirs\Surveys\Controllers\SurveyController@show',
    'as' => 'survey_get'
  ]);
  Route::post('{respondentId}/{respondentType}/survey/{surveySlug}/{responseId?}/{pageName?}', [
    'uses' => 'Sirs\Surveys\Controllers\SurveyController@store',
    'as' => 'survey_post'
  ]);
});

?>