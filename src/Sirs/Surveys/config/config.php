<?php

return [
  'surveysPath' => base_path('resources/surveys'),
  'rulesPath' => app_path('Surveys'),
  'rulesNamespace' => 'App\\Surveys\\',
  'customTemplatePath' => base_path('resources/views/surveys'),
  'rendererConfig' => [
    'cache_path' => storage_path('storage/framework/views'),
  ],
  'routeGroup'=>['middleware' => 'auth'],
  'chromeTemplate'=>'app',
];

?>