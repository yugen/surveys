<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Documents\Blocks\Questions\QuestionBlock;


abstract class BoundedQuestion extends QuestionBlock
{
    protected $min;
    protected $max;

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
      parent::parse($simpleXmlElement);
      $this->setMin($this->getAttribute($simpleXmlElement, 'min'));
      $this->setMax($this->getAttribute($simpleXmlElement, 'max'));
    }

    public function setMin($min)
    {
        if($this->boundaryIsValid($min)){
          $this->min = $min;
          return $this;
        }else{
          throw new \InvalidArgumentException('Invalid min given for quesiton '.$this->getName());
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
          throw new \InvalidArgumentException('Invalid max given for quesiton '.$this->getName());
        }
    }

    public function getMax()
    {
        return $this->max;
    }

  abstract public function boundaryIsValid($bound);
}