<?php

return [
  'surveysPath' => base_path('resources/surveys'),
  'defaultTemplatePath' => __DIR__.'/../Views/',
  'customTemplatePath' => base_path('resources/views/surveys'),
  'rendererConfig' => [
    'cache_path' => storage_path('storage/framework/views'),
  ],
];

?>