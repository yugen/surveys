# Sirs Surveys #

A package for building surveys using xml and rendering them to html in Laravel projects.

## Getting started

### Conceptual overview
It's helpful to understand the concepts behind the sirs/surveys package before diving into the technical details.

#### Terms
* **SurveyDocument**: An XML document that describes the structure of a survey using the *survey.xsd* schema.
* **Survey**: The model that represents surveys stored in the database
* **Response**: The model that represents a survey response as stored in the database.
* **Respondent**: The model in the application that is responding to the survey's questions. The model that the response should be associated with.
* **Rules**: A class that defines how the survey behaves.
* **WorkflowStrategies**: A class that is called when a SurveyResponseEvent for a particular survey is fired.

### Quick Start Guide
1. Install the package
1. Configure: update /config/surveys.php
2. Create Survey definition directory: /resources/surveys
3. Create custom template directory: /resources/views/surveys
4. write your first survey and save in /resources/surveys
** See survey definition schema docs in the wiki
5. Run ```php artisan survey:new <path_to_survey>``` to create a migration and rules file
6. To replace the migration run ```php artisan survey:migration <path_to_survey>```

### Installation

1. run `composer require sirs/surveys`
2. add the service provider to config/app.php: `Sirs\Surveys\SurveysServiceProvider::class,`
3. add the service provider to your app config: `Sirs\Surveys\SurveysServiceProvider::class,`
4. Publish the stylesheets and config file: `$ php artisan vendor:publish`

## Usage Notes
* Because the Response model refers to a number of different response tables the best way to interact with responses is through survyeys:
  ```
    $respondent = new Participant();
    $survey = class_survey()::findBySlug('svyslug'); //see bindings in configuration for info on class_path()
    $responses = $survey->responses;
    $newResponse = $survey->getNewResponse($respondent);
  ```
* New responses can be accessed via the route 
  ```
    /{full-respondent-class-name}/{id}/survey/{survey-slug}/new` i.e. `app-participant/1/survey/screener1/new
  ```
* Existing response can be accessed via the route 
  ```
    /{full-respondent-class-name}/{id}/survey/screener1/{response_id}`
  ```
* Latest existing or new response can be accessed via the route 
  ```
    /{full-respondent-class-name}/{id}/survey/screener1
  ```

## Configuration

  * **editAfterFinalized**
  : *(bool)* - Allow users to edit responses after they've been marked finalized
  * **surveysPath**: *(string)* - Path to survey XML documents
  * **rulesPath**: *(string)* - Path to rules classes
  * **rulesNamespace**: *(string)* - Namespace of rules classes
  * ~~**customTemplatePath**: *(string)* - Path to custom templates~~ REMOVED
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
  * **defualt_templates**: *(array)* - An array defining default templates for blocks.
  * **validation_messages**: *(array)* - An array of Laravel validatition custom messages.  You can customize the message for a rule globally or for a specific field as per the [Laravel Validation Documentation](https://laravel.com/docs/5.8/validation#customizing-the-error-messages)
  * **bindings**: 
        Bindings can be used to override the classes used for the Survey and Response models, allowing you to do things like define relationships, fire custom events, and override default behavior. Leave this section of the config commented out to use the models provided by the package.

        NOTE: This is optional. If not set Sirs\Surveys\Models\Response and Sirs\Surveys\Models\Survey will be used.
    * **models**:
      * **Survey**: *(string)* - App\Survey::class (must implement  *Sirs\Surveys\Contracts\SurveyContract*)
        * the `class_surve()` will always return the configured Survey model.  Using `class_survey::methond()` instead of `\Sirs\Surveys\Models\Surveys::method()` is recommended for future-proofing your application from changing configurations.
      * **Response**: *(string)* - App\SurveyResponse::class (must implement *Sirs\Surveys\Contracts\SurveyResponse*)

## Artisan commands
  * `make:survey` - Creates a stub survey file
  * `make:survey-rules` - Create a new survey rules class
  * `survey:migration` - Create/update migrations from survey document
  * `survey:migrations-all` - Rebuild migrations for all surveys in the project.
  * `survey:new` - Creates new survey migration and rules document
  * `survey:refresh` - Refresh response tables
  * `survey:rules` - Create/update rules file from survey document
  * `survey:validate` - Validate a survey against the schema
  * `survey:workflow` - Create SurveyWorkflowStrategy class for survey of type

Run `php artisan list | grep survey` for a complete list of artisan commands related to surveys.  Run `php artisan help <command>` for details on commands.

## Creating Surveys
You can create surveys 2 ways

* All at once:
  1. Create XML stub and Rules: `$ php artisan make:survey survey_name`
  1. Add questions to your survey.
  2. Create migration: `$ php artisan survey:migration path/to/survey/survey_name.xml`
* By hand:
  1. Add survey XML document to your surveysPath (see configuration)
  1. Create a rules class and a migration with `$ php artisan survey:new path/to/survey/document`
      * Alternately you can run those seperately:
        1. Create rules: `$ php artisan survey:rules path/to/survey/document`
        1. Create migration: `$ php artisan survey:migration path/to/survey/document`

## Defining behavior
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

## Customization
There are several was to customize the surveys package

### Temlates ###
Survey package templates use the `surveys::` blade namespace (i.e. `surveys::questions.multiple_choice.select`).

### Overriding default templates
To specify a template as the default for a block type use the `surveys.default_templates` config.

Alternately, you can override default templates by adding a template with the same relative path to `resources/views/vendor/surveys`.  For example, if you wanted to override the default page template, simply add `resources/views/vendor/surveys/containers/page/page.blade.php` to your project.

### Custom templates
#### Template foundations
Every element of a survey that can be rendered extends the RenderableBlock class.  Within the template used to render a RenderableBlock you have access to the following variables:
* $context - The context populated by the survey package and/or the rules class beforeShow method(s).  By default $context includes
  * $response - the survey response
  * $survey - an array with summary information about the survey
* $renderable - the block the template is rendering. For example, in the template for a multiple-choice question $renderable is the QuestionBlock object; in a template for a question-group $renderable is the question-group block

With access to the block being rendered you have everything you need to render the block and it's children.  In the case of *containers* (blocks that can contain other blocks) you can access their children as an associative array with the block name as the key at `$renderable->contents`.

### Examples
See example [xml](https://bitbucket.org/shepsweb/sirs-surveys/src/dbdb6fdfac1007d8a747a08c23dab44b1b2100ef/examples/resources/surveys/pages/include_page_3.xml?at=master&fileviewer=file-view-default) and [blade template](https://bitbucket.org/shepsweb/sirs-surveys/src/dbdb6fdfac1007d8a747a08c23dab44b1b2100ef/examples/resources/views/?at=master)

In addition to checking for views in `surveys::` views namespace, the package will also check to see if the specified custom template exists in the default view namespace.  This is currently included to support projects using pre-4.0 custom template organization.


## XML Schema
See examples in [source examples dir](https://bitbucket.org/shepsweb/sirs-surveys/src/dbdb6fdfac1007d8a747a08c23dab44b1b2100ef/examples/?at=master)

## Containers
Containers are blocks that have other elements as contents.

### Attributes (applies to all containers)
* **name** - string - *required*|*unique*: This is used as the variable/column name
* **id** - string: Analogous to html attribute
* **class** - string: Analogous to html attribute

### Children
#### Template
Specifies an alternate template to use for the block.
```
  <template source="path.to.template"></template>
```
#### Metadata
Allows for the definition of additional information about the renderable block.  
Requires one or more `<datum>` children. Keys and values can be defined as attributes or children of `<datum>` tag
```
<metadata>
  <datum key="datum_key" value="datum-value"></datum>
  <datum>
    <key>Another Key</key>
    <value>
      <![CDATA[my cdata-requiring value here.]]>
    </value>
  </datum>
</metadata>
```

#### Renderable Blocks (see below for details)
* **QuestionGroup**
* **Likert**
* **Html**
* **Question**

### Descendants

#### Page
The page can contain 1 or more child blocks that are displayed as a page.

##### Included Templates
* containers.page.page (default)

#### QuestionGroup
An arbitrary grouping of other elements

##### Children
* **QuestionGroup**
* **Likert**
* **Html**
* **Question**

##### Included Templates
* block_default (default) - Container that renders each child.

#### Likert
A specialized container for applying a single set of options to a number of questions.

##### Children
* **Prompt** - Prompt for the set of questions
* **question** - Any number of question elements
* **options** - A group of options that are used for all questions in the likert

##### Included Templates
* containers.likert.btn_group_likert (default) - Bootstrap button based likert display.
* containers.likert.traditional_likert - Traditional likert w/ radio buttons and option labels at top.

## Html

## Questions
### Question
The root question: expects a string based response by default.
```
<question name="name" id="name">
    <question-text>question-text</question-text>
</question>
```

#### Options (applies to all question types)
* **name** - string - *required*|*unique*: This is used as the variable/column name
* **id** - string: Analogous to html attribute
* **class** - string: Analogous to html attribute
* **placeholder** - string: Analogous to html attribute
* **disabled** - boolean [false]: Analogous to html attribute
* **readonly** - boolean [false]: Analogous to html attribute
* **hidden** - boolean [false]: Indicates question should be hidden
* **refusable** - boolean [false]: Indicates the respondent should be able to mark the question as refused to answer;
* **refuse**-label - string [refused]: Label used for refusable questions
* **data-format** - string [varchar]: Data type used for sql column.  All valid mysql column types supported.
* **validation**-rules - string: Laravel validation rules string.

#### Children (applies to all question types)
##### QuestionText
The question prompt or text.
```
  <question-text>Question text goes here</question-text>
```
##### Template
Specifies an alternate template to use for rendering the block.
```
  <template source="path.to.template"></template>
```
##### Metadata
Allows for the definition of additional information about the question.  Requires one or more `<datum>` children. Keys and values can be defined as attributes or children of `<datum>` tag
```
<metadata>
  <datum key="datum_key" value="datum-value"></datum>
  <datum>
    <key>Another Key</key>
    <value>
      <![CDATA[my cdata-requiring value here.]]>
    </value>
  </datum>
</metadata>
```
#### Included Templates
* questions.text.default_text (default) - text input
* questions.text.inline - inline text input
* questions.large_text - textarea (should be used with `data-format="text"` or similar, or validated to prevent response truncation when stored)
* questions.other_field - inline text input for use as other details.

### MultipleChoice
A question with a set number of possible answers.  MulipleChoice questions can be single-select or multi-select (if `num-selectable` attribute is greater than 1).
```
<multiple-choice name="my_mc" id="my_mc" num-selectable="1">
    <question-text>
        <![CDATA[What is your favorite color]]>
    </question-text>
    <options>
        <option name="my_mc1">
            <value>1</value>
            <label>blue</label>
        </option>
        <option name="my_mc2">
            <value>2</value>
            <label>green</label>
        </option>
        <option name="my_mc3">
            <value>3</value>
            <label>yellow</label>
        </option>
        <option name="my_mc4">
            <value>4</value>
            <label>red</label>
        </option>
        <option name="my_mc5">
            <value>5</value>
            <label>black</label>
        </option>
    </options>
</multiple-choice>
```
#### Attributes
* **num-selectable** - integer [1]: Number of options the respondent can select.

#### Child Tags
* options - takes a list of option tags or a data-source tag.  data-source's URI attribute supports a api endpoint URL, a function, or a class method using laravel's 'action' syntax (ClassName@method).  For methods and functions parameters can be passed in the format `ClassName@method:param1=val1,param2=val2`

##### Options
###### Attributes
* name - string - *required*
* id
* class
* exclusive - integer: integer indicates group

###### Metadata
Allows for the definition of additional information about the renderable block.  
Requires one or more `<datum>` children. Keys and values can be defined as attributes or children of `<datum>` tag
```
<metadata>
  <datum key="datum_key" value="datum-value"></datum>
  <datum>
    <key>Another Key</key>
    <value>
      <![CDATA[my cdata-requiring value here.]]>
    </value>
  </datum>
</metadata>
```


#### Included Templates
##### Single-select
* questions.multiple_choice.radio_group (default) - bootstrap button style radio buttons
* questions.multiple_choice.radio_group_vertical - radio buttons
* questions.multiple_choice.select - html select
##### Multi-select
* questions.multiple_choice.checkbox_group (default - multi-select) - checkboxes
* questions.multiple_choice.select - html select

### Number
A question which requires a number for a value between **min** to **max**.
```
<number name="Height" required="1" data-format="int">
  <template source="Height_Inches.blade.php" />
  <question-text>Height (inches)</question-text>
</number>
```

### Numeric Scale
A numeric-scale with options ranging from **min** to **max** at **interval**. (Extends Number)
```
<numeric-scale name="test" min="1" max="5" interval="1">
    <question-text>Numeric scale question.</question-text>
    <legend>
        <item>
            <label>First</label>
            <value>1</value>
        </item>
        <item>
            <label>Last</label>
            <value>5</value>
        </item>
    </legend>
</numeric-scale>
```
#### Attributes
*  min - interger - *required*
*  max - integer - *required*
*  interval - integer - interval between options - default: 1
*  reverse - boolean - if 1 list scale in reverse order

#### Legend:
The legend describes the meaning of the options.  It is made up of any number of items each of which have a `<label>` and `<value>`.  When rendered the items will be evenly distributed above the options.


### Date
A question collecting date information between optional **min** and **max** boundaries
```
<date name="DOB" required="1" placeholder="MM/DD/YYYY" min="1940-01-01" max="1990-01-01">
  <question-text>Date of Birth</question-text>
</date>
```
Validates against **min** and **max** attributes if set.

#### Attributes
*  min - date in the format of 'yyyy-mm-dd'
*  max - date in the format of 'yyyy-mm-dd'

#### Included Templates
* questions.date - input w/ date picker

### Month
Single-select multiple choice question with months (1-12) as options.

#### Included Templates
* questions.multiple_choice.select (default)
* Any template that supports single-select multiple-choice questions


### Year
Numeric scale question for years as options.
```
<year min="now" max="+30 years">
  <question-text>When will you retire?</question-text>
</year>
```

#### Attributes
*  min - parsable date string (i.e. '2019-01-01', '09/16/1977', 'tomorrow', '+1 year')
*  max - parsable date string (i.e. '2019-01-01', '09/16/1977', 'tomorrow', '+1 year')

#### Included Templates
* questions.multiple_choice.select (default)
* Any template that supports single-select multiple-choice questions


### Time
A question for collected time information.

Validates against **min** and **max** attributes if set.

#### Attributes
*  min - time in the format of 'hr:min:sec
*  max - time in the format of 'hr:min:sec

#### Included Templates
* questions.time - input w/ time picker




### Who do I talk to? ###

* TJ Ward - jward3@email.unc.edu
* Alex Harding - ahhardin@email.unc.edu