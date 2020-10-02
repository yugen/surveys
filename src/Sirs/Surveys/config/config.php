<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Surveys path
    |--------------------------------------------------------------------------
    |
    | The path to the survey xml files
    |
    */

    'surveysPath' => base_path('resources/surveys'),

    /*
    |--------------------------------------------------------------------------
    | Rules path
    |--------------------------------------------------------------------------
    |
    | The path to the survey rules classes
    |
    */
    'rulesPath' => app_path('Surveys'),


    /*
    |--------------------------------------------------------------------------
    | Rules Namespace
    |--------------------------------------------------------------------------
    |
    | Namespace for rules classes
    |
    */
    'rulesNamespace' => 'App\\Surveys\\',

    /*
    |--------------------------------------------------------------------------
    | UUIDs for Responses
    |--------------------------------------------------------------------------
    |
    | Use UUIDs for Responses?
    |
    */
    'useUuidForResponses' => false,

    /*
    |--------------------------------------------------------------------------
    | Data Summary
    |--------------------------------------------------------------------------
    |
    | Show the Data Summary link
    |
    */
    'showDataSummary' => env('SHOW_DATA_SUMMARY', false),

    /*
    |--------------------------------------------------------------------------
    | Route group
    |--------------------------------------------------------------------------
    |
    | Route group definition for survey routes
    |
    */
    'routeGroup' => ['middleware' => ['web', 'auth']],


    /*
    |--------------------------------------------------------------------------
    | Cache documents
    |--------------------------------------------------------------------------
    |
    | Boolean for whether to cache documents.
    | Caching documents will speed up render and validation times dramatically
    | in production.
    |
    */
    'cacheDocuments' => (env('APP_DEBUG')) ? false : true,

    /*
    |--------------------------------------------------------------------------
    | Chrome template
    |--------------------------------------------------------------------------
    |
    | Template that the page's template extends.  This my be your projects base template
    | or one specifically for surveys.
    */
    'chromeTemplate' => 'app',

    /*
    |--------------------------------------------------------------------------
    | Edit after Finalized
    |--------------------------------------------------------------------------
    |
    | Boolean for whether or not to allow users to edit a survey response after it is marked finalized.
    |
    */
    'editAfterFinalized' => true,

    /*
    |--------------------------------------------------------------------------
    | Custom template path
    |--------------------------------------------------------------------------
    |
    | Alternate path at in which to look for survey templates
    |
    */
    'customTemplatePath' => base_path('resources/views/surveys'),

    /*
    |--------------------------------------------------------------------------
    | Refused label
    |--------------------------------------------------------------------------
    |
    | Label and value to use for refused option on refusable questions
    |
    */
    'refusedLabel' => 'Refused',
    'refusedValue' => -77,

    /*
    |--------------------------------------------------------------------------
    | Auto-save configuration
    |--------------------------------------------------------------------------
    |
    | Boolean for whether to cache documents.
    | Caching documents will speed up render and validation times dramatically
    | in production.
    |
    */
    'autosave' => [
        'enabled' => true,
        'frequency' => 10000, // time in miliseconds
        'notify' => true,
        'notify_time' => 2500
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Templates
    |--------------------------------------------------------------------------
    |
    | Mapping of xml elements to their default templates.  These can be templates
    | included in the package or your own custom templates.  In the case of custom
    | templates be sure to use the full blade path `my.custom.template`
    |
    */

    'default_templates' => [
        'page' => 'containers.page.page',
        'date' => 'questions.date.date',
        'duration' => 'questions.number.duration',
        'multiple_choice' => [
            'single' => 'questions.multiple_choice.radio_group',
            'multi' => 'questions.multiple_choice.checkbox_group',
            'json' => 'questions.multiple_choice.checkbox_group_array',
        ],
        'number' => 'questions.number.number',
        'numeric_scale' => 'questions.number.numeric_scale',
        'question' => 'questions.text.default_text',
        'time' => 'questions.time.time'
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation messages
    |--------------------------------------------------------------------------
    |
    | Custom messages for validation. Using the standard Laravel validation
    | messages format. https://laravel.com/docs/master/validation#custom-error-messages
    |
    */
    'validation_messages' => [
        // 'my_field.validation_rule' => 'My custom mssage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Data source cache life
    |--------------------------------------------------------------------------
    |
    | Length of time in seconds to cache responses for option data sources.
    |
    */
    'datasource_cachelife' => 1200,


    'bindings' => [
        /*
        |--------------------------------------------------------------------------
        | bindings.models
        |--------------------------------------------------------------------------
        |
        | Bindings to override the default package survey and response models
        |
        */
        'models' => [
            'Survey' => Sirs\Surveys\Survey::class,
            'Response' => Sirs\Surveys\Response::class
        ]
    ],
];
