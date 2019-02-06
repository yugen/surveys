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
    'routeGroup'=>['middleware' => ['web', 'auth']],
    'chromeTemplate'=>'app',
    'cacheDocuments'=>(env('APP_DEBUG')) ? false : true,
    'refusedLabel'=>'Refused',
    'autosave'=>[
        'enabled' => true,
        'frequency' => 10000, // time in miliseconds
        'notify' => true,
        'notify_time' => 2500
    ],
    'default_templates' => [
        'page' => 'containers.page.page',
        'date' => 'questions.date.date',
        'duration' => 'questions.number.duration',
        'multiple_choice' => [
            'single' => 'questions.multiple_choice.radio_group',
            'multi' => 'questions.multiple_choice.checkbox_group',
        ],
        'number' => 'questions.number.number',
        'numeric_scale' => 'questions.number.numeric_scale',
        'question' => 'questions.text.default_text',
        'time' => 'questions.time.time'
    ]
    // 'bindings' => [
    //   'models' => [
    //     'Survey' => App\Survey::class
    //     'Response' => App\SurveyResponse::class
    //   ]
    // ],
];
