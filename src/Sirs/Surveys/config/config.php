<?php

return [
  'surveysPath' => base_path('resources/surveys'),
  'rulesPath' => app_path('Surveys'),
  'defaultTemplatePath' => __DIR__.'/../Views/',
  'customTemplatePath' => base_path('resources/views/surveys'),
  'rendererConfig' => [
    'cache_path' => storage_path('storage/framework/views'),
  ],
  'routeGroup'=>['middleware' => 'auth'],
  'chromeTemplate'=>'app',
];

?>