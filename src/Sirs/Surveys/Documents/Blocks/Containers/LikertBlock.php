<?php

namespace Sirs\Surveys\Documents\Blocks\Containers;

use Sirs\Surveys\Contracts\HasOptionsInterface;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\Documents\Blocks\OptionBlock;
use Sirs\Surveys\HasOptionsTrait;

class LikertBlock extends ContainerBlock implements HasOptionsInterface
{
  use HasOptionsTrait;

  protected $refusable;

  public function __construct($xml = null)
  {
    parent::__construct($xml);
    $this->defaultTemplate = 'containers.likert.btn_group_likert';
  }


  public function parse(\SimpleXMLElement $simpleXmlElement){
    $this->setPrompt((string)$simpleXmlElement->prompt[0]);
    $this->parseOptions($simpleXmlElement);
    $this->setRefusable($this->getAttribute($simpleXmlElement, 'refusable'));
    parent::parse($simpleXmlElement);
  }

  public function setPrompt($prompt)
  {
      $this->prompt = $prompt;
      return $this;
  }

  public function getPrompt()
  {
      return $this->prompt;
  }

  public function setRefusable($value)
  {
    $this->refusable = ($value) ? true : false;
    if( $this->refusable ){
      $refusedOption = new OptionBlock('refused');
      $refusedOption->setValue(-77);
      $refusedOption->setLabel('Refused');
      $this->appendOption($refusedOption);
    }
    return $this;
  }

  public function getRefusable()
  {
    return ($this->refusable) ? true : false;
  }

}
