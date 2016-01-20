<?php

namespace Sirs\Surveys;

class DateQuestion extends QuestionBlock
{
  protected $min;
  protected $max;

  public function __construct($xml = null)
  {
    parent::__construct($xml);
    $this->defaultTemplate = 'questions/date/default.blade.php';
    $this->defaultDataFormat = 'date';
  }

  function parse()
  {
    parent::parse();
    if( $this->xmlElement->min[0] ){
      $this->setMin($this->xmlElement->min[0]->__toString(), 'min');
    }
    if( $this->xmlElement->max[0] ){
      $this->setMax($this->xmlElement->max[0]->__toString(), 'max');
    }
  }

  public function setMin($min)
  {
    $this->min = $min;
    return $this;
  }

  public function getMin()
  {
    return $this->min;
  }

  public function setMax($max)
  {
    $this->max = $max;
    return $this;
  }

  public function getMax()
  {
    return $this->max;
  }

}
