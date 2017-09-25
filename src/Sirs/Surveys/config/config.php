<?php

return [
  'editAfterFinalized'=>true,
  'surveysPath' => base_path('resources/surveys'),
  'rulesPath' => app_path('Surveys'),
  'rulesNamespace' => 'App\\Surveys\\',
  'customTemplatePath' => base_path('resources/views/surveys'),
  'rendererConfig' => [
    'cache_path' => storage_path('storage/framework/views'),
  ],
  'routeGroup'=>['middleware' => 'auth'],
  'chromeTemplate'=>'app',
  'cacheDocuments'=>( env('APP_DEBUG') ) ? false : true,
  'refusedLabel'=>'Refused',
  'autosave'=>[
    'enabled' => true,
    'frequency' => 10000, // time in miliseconds
    'notify' => true,
    'notify_time' => 2500
  ],
  // 'bindings' => [
  //   'models' => [
  //     'Survey' => App\Survey::class
  //     'Response' => App\SurveyResponse::class
  //   ]
  // ],
];

?>