# Change Log

## 2.0.0 - XXXX-XX-XX
#### Breaking Changes
* Responses are now soft-deleted.  That means that you will have to add a deleted_at timestamp field to your existing response tables.
* Added SurveyRules base class that all survey rules should inherit from.  Your rules classes should now extend SurveyRules to take advantage of rules pretext.

#### Non-breaking changes
* Added support for save_and_exit button.
* 'Save' and 'Save and Exit' can be hidden by passing `$hideSave = 1` and `$hideSaveExit = 1` respectively
* Added support for survey-id attribute on the survey tag.  If present it will be used to set the survey's id in the surveys table.
* Extracted survey control logic to SurveyControlService.
* Added instance var $pretext to SurveyRules and method that sets SurveyRules::pretext from request.
* Added survey document caching to speed up load times. config('surveys.cacheDocuments') controls caching.  If true survey docs are cached.

## 1.2.2 - 1.2.5 - 2016-07-26
* Fixed bug in QuestionFactory
* Fixed bad option rendering bug
* Fixed include bug that resulted in only every other included being pulled into the survey
* Removed errant dd() statement

## 1.2.1 - 2016-07-25
* Added support for *parameter* elements on survey tag and containers.  See [data-source documentation](https://bitbucket.org/shepsweb/sirs-surveys/wiki/data-source) for usage.
* Options tag now uses both data-source and option tags to populate options for a multiple-choice question.

## 1.2.0 - 2016-07-21
* Added support for *rules-class* attribute on survey tag to allow users to specify a rules class for the survey.  If no rules-class is specified then original rules-class is used.
* Added console command to create new rules *make:survey-rules <RulesClassName>*.  Creates rules file and test in tests/Surveys.
* survey:rules command now creates a test in tests/Surveys when run.
* Fixed bug related to naming migration classes that led to failures when runing migrate:refresh.

## 1.1.1 - 2016-07-08
* Include tag that allows the inclusion of partial xml files in a survey
    * survey.xsd - Added include tag definition with attribute source and made it a possible child of <survey> or <container>
    * Documents/XmlDocument.php - added compile method to replace includes with referenced source files.
    * Documents/SurveyDocument.php - added xml validation before parent::__construct() to validate pre-compiled xml.
* Support for survey-wide beforeShow, beforeSave, afterSave.
* Added support for destination_page to SurveyController on save navigation to allow random access navigation after save.
* Added make:survey command to package to stub a survey xml file.
* fixed naming of migration class file when survey name has underscores in it.

## 1.0.3 - 2016-06-06
* survey.xsd - Added minOccurs="0" to question schema to allow text questions without template or question-text.
* survey.xsd - Added minOccurs="0" to numeric-range legend child to allow for no legend.

## 1.0.2 - 2016-06-03
* Added questions.text.large_text question template for text areas.
* SurveyController@store is now setting any response attributes that are not received in the HTTP Request to null.  This resolves an error in which which fields hidden due to skip triggers were still submitting values in some cases.
* Improved XML validation error messages.  You'll now get the specific error message, the line number, and the line on which the error occurred.
* Survey XML validation is now being called before rendering the survey and throwing exceptions if the survey xml is not valid.