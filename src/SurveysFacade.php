<?php 

namespace Sirs\Surveys;

use Illuminate\Support\Facades\Facade;

/**
* SUrveys facade
*/
class SurveysFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'surveys';
    }
}
