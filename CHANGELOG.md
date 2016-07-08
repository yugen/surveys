# Change Log
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