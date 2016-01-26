<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\HasOptionsInterface;

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
