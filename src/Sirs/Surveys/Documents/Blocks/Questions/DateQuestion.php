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
    $this->defaultTemplate = 'questions/date/default.blade.php';
    $this->defaultDataFormat = 'date';
  }

  public function boundaryIsValid($boundary)
  {
      if( is_string($boundary) ){
          return (boolean)(preg_match('/^\d\d\d\d-\d\d-\d\d?$/', $boundary));
      }
      return false;
  }

}
