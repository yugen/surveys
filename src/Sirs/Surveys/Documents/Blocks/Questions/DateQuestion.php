<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Documents\Blocks\Questions\BoundedQuestion;

class DateQuestion extends BoundedQuestion
{
  protected $min;
  protected $max;

  public function __construct($xml = null)
  {
    parent::__construct($xml);
    $this->defaultTemplate = 'questions.date.date';
    $this->defaultDataFormat = 'date';
  }

  public function boundaryIsValid($boundary)
  {
      if( is_string($boundary) ){
          return (boolean)(preg_match('/^\d\d\d\d-\d\d-\d\d?$/', $boundary));
      }elseif($boundary === null){
        return true;
      }
      return false;
  }

  protected function getValidationRules()
  {
    $validations = parent::getValidationRules();
    if( $this->min ){
      $validations[] = 'after:'.$this->min;
    }
    if( $this->max ){
      $validations[] = 'before:'.$this->max;
  }
    return $validations;
  }

}
