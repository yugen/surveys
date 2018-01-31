# Sirs Surveys #

A package for building surveys using xml and rendering them to html in Laravel projects.

## Contents
1. [Getting Started](#getting-started)
  1. [Conceptual Overview](#concepts)
  2. [Quick Start](#quick-start)
  3. [Installation](#installation)
2. [Configuration](#config)
3. [Creating Surveys](#creating)
4. [Behavior](#behavior)
5. [Customization](#customization)
6. [XML](#xml)

## <a id="getting-started"></a>Getting started

### <a id="concepts"></a>Conceptual overview
It's helpful to understand the concepts behind the sirs/surveys package before diving into the technical details.

#### Terms
* **SurveyDocument**: An XML document that describes the structure of a survey using the *survey.xsd* schema.
* **Survey**: The model that represents surveys stored in the database
* **Response**: The model that represents a survey response as stored in the database.
* **Respondent**: The model in the application that is responding to the survey's questions. The model that the response should be associated with.
* **Rules**: A class that defines how the survey behaves.
* **WorkflowStrategies**: A class that is called when a SurveyResponseEvent for a particular survey is fired.

### <a id="quick-start"></a>Quick Start Guide
1. Install the package
1. Configure: update /config/surveys.php
2. Create Survey definition directory: /resources/surveys
3. Create custom template directory: /resources/views/surveys
4. write your first survey and save in /resources/surveys
** See survey definition schema docs in the wiki
5. Run ```php artisan survey:new <path_to_survey>``` to create a migration and rules file
6. To replace the migration run ```php artisan survey:migration <path_to_survey>```

### <a id="installation"></a>Installation

1. run `composer require sirs/surveys`
2. add the service provider to config/app.php: `Sirs\Surveys\SurveysServiceProvider::class,`
3. add the service provider to your app config: `Sirs\Surveys\SurveysServiceProvider::class,`
4. Publish the stylesheets and config file: `$ php artisan vendor:publish`

## <a id="config"></a>Configuration

  * **editAfterFinalized**
  : *(bool)* - Allow users to edit responses after they've been marked finalized
  * **surveysPath**: *(string)* - Path to survey XML documents
  * **rulesPath**: *(string)* - Path to rules classes
  * **rulesNamespace**: *(string)* - Namespace of rules classes
  * **customTemplatePath**: *(string)* - Path to custom templates
  * **rendererConfig**:
    * **cache_path**: *(string)* - Path to view cache
  * **routeGroup**: *(array)* - Route group to apply to survey routes
  * **chromeTemplate**: *(string)* - Blade template to use as chrome for survey views
  * **cacheDocuments**: *(bool)* -
  * **refusedLabel**: *(string)* - Label to use for refused options on refusable questions
  * **autosave**:
    * **enabled**: *(bool)* - Enable/disable autosaving,
    * **frequency**: *(int)* - Frequency of autosaves in milliseconds
    * **notify**: *(bool)* - Notify the user of the autosave
    * **notify_time**: *(int)* - Length of time the notification is displayed
    * **bindings**: 
          Bindings can be used to override the classes used for the Survey and Response models, allowing you to do things like define relationships, fire custom events, and override default behavior. Leave this section of the config commented out to use the models provided by the package.
      * **models**:
        * **Survey**: *(string)* - App\Survey::class (must implement  *Sirs\Surveys\Contracts\SurveyContract*)
        * **Response**: *(string)* - App\SurveyResponse::class (must implement *Sirs\Surveys\Contracts\SurveyResponse*)

## <a id="creating"></a>Creating Surveys
You can create surveys 2 ways

* All at once:
  1. Create XML stub and Rules: `$ php artisan make:survey survey_name`
  2. Create migration: `$ php artisan survey:migration path/to/survey/survey_name.xml`
* By hand:
  1. Add survey XML document to your surveysPath (see configuration)
  1. Create rules: `$ php artisan survey:rules path/to/survey/document`
  1. Create migration: `$ php artisan survey:migration path/to/survey/document`

## <a id="behavior"></a>Defining behavior
### Rules
Once you've created a rules class for your survey you can begin defining behavior.  The rules class supports a number of methods that allow you to manipulate the behavior of the entire survey or a specific page.  

#### Attributes

* **$response** - resolves to instance of *Sirs\Surveys\Models\Response* or binding specified in the config.
* **$survey** - resolves to instance of *Sirs\Surveys\Models\Survey* or binding specified in the config.
* *Mixed* **$respondent** - The response's respondent

 #### Methods

##### Survey-wide methods

* **beforeShow(void)**:*array $context* - executed before showing a page.  Allows you to add data to context passed to the view before rendering: `$context['beans'] = 'monkeys';`
* **beforeSave(void)** - Executes logic before the response data is saved.
* **afterSave(void)** - Executes logic after the response data is saved.
* **navigate(['page'=>'pageName', 'nav'=>'navType'])**:*int*|null - Allows for custom navigation behavior on 'next' or 'prev' navigation.  Return a **page number** to override the default navigation; return **null** for default navigation behavior. 
* **getRedirectUrl(void)** - Gets url for redirection when exiting survey (i.e. *Save & Exit* or *Finalize* )
* *Advanced* **setPretext(array $requestData)** - Stores info from request to the session 
* *Advanced* **forgetPretext()** - Clears pretext from session

##### Page-specific methods
* **[pageName]BeforeShow()** - Same as beforeShow() but for page with name *pageName*
* **[pageName]BeforeSave()** - Same as beforeSave() but for page with name *pageName*
* **[pageName]AfterSave()** - Same as afterSave() but for page with name *pageName*
* **[pageName]Skip()**:*int* - Allows skiping of page with *pageName*. Called before showing page. Returns integer code for behavior:
  * *0*: default behavior - show the page
  * *1*: skip page - skip this page and proceed with normal navigation i.e. next/prev
  * *2*: finalize - skip this page, finalize the response, and redirect out of survey
  * *3*: exit - exit survey without finalizing.
* **[PageName]GetValidator(Validator $validator)**:*Validator $validator* - Augment the validation defined in the xml with additional rules. Should return validtor object.

#### Reusing rules classes across multiple surveys
There are several ways to reuse rule logic across surveys:

1. Set the **rules-class** attribute to the FQNS of the rules class you want to use.
2. Use traits to reuse rules for pages that appear in more than one survey
3. Inherit from a rules class.

### Response Events & Workflows
Survey responses fire the following events:

* **SurveyResponseStarted** - fired when a response's *started_at* field is updated from null.
* **SurveyResponseSaved** - fired when a response is saved.
* **SurveyResponseFinalized** - fired when a response's *finalized_at* attribute is updated from null.
* **SurveyResponseReopened** - fired when a response's *finalized_at* attribute is updated from a timestamp to null

In addition to creating your own listeners for these events, sirs/surveys lets you create 'WorkflowStrategies' for responses to specfic surveys.  A WorkflowStrategy lets you define all of your event triggered logic for a survey's responses in one file.  You can think of a WorkflowStrategy like an observer for a survey response for a particular survey.

To scaffold a WorkflowStrategy run `$ php artisan survey:workflow survey-slug` where *survey-slug* is the slug for the survey stored in the surveys table.

You'll find your new workflow strategy at */path/to/SurveyRules/Workflows/SurveySlugWorkflowStrategy*, with methods for handling each SurveyResponseEvent ready to go. 

Each WorkflowStrategy class has the following attributes:

* **$response** - The response that triggered the event.
* **$event** - The event that was triggered.

## <a id="customization"></a>Customization
There are several was to customize the surveys package

### Overriding default templates
TODO

### Custome templates
See example [xml](https://bitbucket.org/shepsweb/sirs-surveys/src/dbdb6fdfac1007d8a747a08c23dab44b1b2100ef/examples/resources/surveys/pages/include_page_3.xml?at=master&fileviewer=file-view-default) and [blade template](https://bitbucket.org/shepsweb/sirs-surveys/src/dbdb6fdfac1007d8a747a08c23dab44b1b2100ef/examples/resources/views/?at=master)
TODO

## <a id="xml"></a>XML Schema
See examples in [source examples dir](https://bitbucket.org/shepsweb/sirs-surveys/src/dbdb6fdfac1007d8a747a08c23dab44b1b2100ef/examples/?at=master)

TODO

### Who do I talk to? ###

* TJ Ward - jward3@email.unc.edu
* Alex Harding - ahhardin@email.unc.edu