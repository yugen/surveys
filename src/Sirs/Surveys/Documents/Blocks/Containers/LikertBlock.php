<?php

namespace Sirs\Surveys\Documents\Blocks\Containers;

use Sirs\Surveys\Contracts\HasOptionsInterface;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\HasOptionsTrait;

class LikertBlock extends ContainerBlock implements HasOptionsInterface
{
  use HasOptionsTrait;

  public function __construct($xml = null)
  {
    parent::__construct($xml);
    $this->defaultTemplate = 'containers.likert.btn_group_likert';
  }


  public function parse(){
    $this->setPrompt($this->xmlElement->prompt);
    $this->parseOptions();
    parent::parse();
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

}
