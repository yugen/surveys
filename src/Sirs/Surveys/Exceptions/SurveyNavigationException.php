<?php
namespace Sirs\Surveys\Exceptions;

class SurveyNavigationException extends \Exception
{
  public function __construct($nav, $code=null){
    parent::__constuct('Bad survey navigation.  Navigation command '.$nav.' not valid', $code);
  }
}