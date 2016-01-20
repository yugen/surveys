<?php

namespace Sirs\Surveys;

abstract class BoundedQuestion extends QuestionBlock
{
    protected $min;
    protected $max;

    public function parse()
    {
      parent::parse();
      $this->setMin($this->getAttribute($this->xmlElement, 'min'));
      $this->setMax($this->getAttribute($this->xmlElement, 'max'));
    }

    public function setMin($min)
    {
        if($this->boundaryIsValid($min)){
          $this->min = $min;
          return $this;
        }else{
          throw new \InvalidArgumentException('Invalid min given for quesiton '.$this->getVariableName());
        }
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setMax($max)
    {
        if($this->boundaryIsValid($max)){
          $this->max = $max;
          return $this;
        }else{
          throw new \InvalidArgumentException('Invalid max given for quesiton '.$this->getVariableName());
        }
    }

    public function getMax()
    {
        return $this->max;
    }

  abstract public function boundaryIsValid($bound);
}