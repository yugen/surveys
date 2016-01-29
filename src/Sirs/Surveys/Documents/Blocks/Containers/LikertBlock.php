<?php

namespace Sirs\Surveys\Documents\Blocks\Containers;

use Sirs\Surveys\Contracts\HasOptionsInterface;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;

class LikertBlock extends ContainerBlock implements HasOptionsInterface
{
  protected $options;

  public function setOptions(array $options){
    $this->options = $options;
    return $this;
  }

  public function getOptions()
  {
    return $this->options;
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
