<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Documents\Blocks\OptionBlock;

trait HasOptionsTrait{
  protected $options;

  public function parseOptions(){
      if( $this->xmlElement->options->option ){
          foreach( $this->xmlElement->options->option as $option ){
            $this->appendOption(new OptionBlock($this->name, $option));
          }
      }
  }

  public function setOptions(array $options)
  {
      $this->options = $options;
      return $this;
  }

  public function getOptions()
  {
      return ($this->options) ? $this->options : [];
  }

  public function appendOption(OptionBlock $option)
  {
    $this->options[] = $option;
  }

  public function prependOption(OptionBlock $option)
  {
    array_unshift($this->options, $option);
  }

}