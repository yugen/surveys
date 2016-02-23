<?php

namespace Sirs\Surveys\Documents\Blocks\Containers;

use Sirs\Surveys\Contracts\HasOptionsInterface;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\HasOptionsTrait;

class LikertBlock extends ContainerBlock implements HasOptionsInterface
{
  use HasOptionsTrait;


  public function parse(){
    $this->setPrompt($this->xmlElement->prompt);
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
