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
  'chromeTemplate'=>'layouts.app',
  'cacheDocuments'=>(env('APP_DEBUG')) ? false : true,
  'refusedLabel'=>'Refused',
  'autosave'=>[
    'enabled' => true,
    'frequency' => 10000, // time in miliseconds
    'notify' => true,
    'notify_time' => 2500
  ],
  /**
   * Bindings can be used to override the classes used for the Survey and Response modes.
   * This allows you to do nifty things like define custom relationships, fire custom events, and override default behavior.
   */
  // 'bindings' => [
  //   'models' => [
  //     'Survey' => App\Survey::class
  //     'Response' => App\SurveyResponse::class
  //   ]
  // ],
];
