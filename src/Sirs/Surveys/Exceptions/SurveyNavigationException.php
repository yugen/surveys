<?php
namespace Sirs\Surveys\Exceptions;

class SurveyNavigationException extends \Exception
{
    public function __construct($nav, $code=null)
    {
        $this->message = 'Bad survey navigation.  Navigation command '.$nav.' not valid';
        $this->code = $code;
    }
}
