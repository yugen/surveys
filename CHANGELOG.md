# Change Log
## 1.0.2 - 2016-06-03
* Added questions.text.large_text question template for text areas.
* SurveyController@store is now setting any response attributes that are not received in the HTTP Request to null.  This resolves an error in which which fields hidden due to skip triggers were still submitting values in some cases.
* Improved XML validation error messages.  You'll now get the specific error message, the line number, and the line on which the error occurred.
* Survey XML validation is now being called before rendering the survey and throwing exceptions if the survey xml is not valid.