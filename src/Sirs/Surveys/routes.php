<?php

$routeGroup = (config('surveys.routeGroup')) ? config('surveys.routeGroup') : []; 

Route::group($routeGroup, function(){
  Route::get('
    {respondentType}/{respondentId}/survey/{surveySlug}/{responseId?}', 
    [
      'uses' => 'Sirs\Surveys\Controllers\SurveyController@show',
      'as' => 'survey_get'
    ]
  );
  
  Route::put(
    'api/{respondentType}/{respondentId}/survey/{surveySlug}/{responseId?}', 
    [
      'uses' => 'Sirs\Surveys\Controllers\SurveyController@autosave',
      'as'=>'surveys.autosave'
    ]
  );

  Route::post(
    '{respondentType}/{respondentId}/survey/{surveySlug}/{responseId?}', 
    [
      'uses' => 'Sirs\Surveys\Controllers\SurveyController@store',
      'as' => 'survey_post'
    ]
  );
  
  Route::resource(
    'surveys/{surveySlug}/responses', 
    'Sirs\Surveys\Controllers\ResponseController'
  );

  Route::get(
    'surveys/reports', 
    'Sirs\Surveys\Controllers\ReportController@index'
  );
  
  Route::any(
    'surveys/{surveySlug}/report/detail/{pageName?}/{variableName?}', 
    'Sirs\Surveys\Controllers\ReportController@detail'
  );
  
  Route::any(
    'surveys/{surveySlug}/report', 
    'Sirs\Surveys\Controllers\ReportController@overview'
  );
  
  Route::resource(
    'surveys/data-dictionary', 
    'Sirs\Surveys\Controllers\DictionaryController', 
    ['only'=>['index','show']]
  );
});

?>