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
      }elseif( $this->xmlElement->options->{'data-source'} ){
          $dataSourceEl = $this->xmlElement->options->{'data-source'};
          // dd($dataSourceEl);
          $dataSourceUri = $this->getAttribute($dataSourceEl, 'URI');
          // dd($dataSourceUri);
          $this->getOptionsFromDataSource($dataSourceUri);
      }else{
        throw new \Exception('No options or datasource found');
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

  protected function getOptionsFromDataSource($dataSourceUri)
  {
    $responseString = file_get_contents(url($dataSourceUri));
    if( $responseString === false ){ throw new \Exception('Failed to got data from '.$dataSourceUir);}
    $sourceData = json_decode($responseString);
    foreach( $sourceData as $idx => $optionData ){
      $optionBlock = new OptionBlock($this->name);
      $optionBlock->setValue($optionData->id);
      $optionBlock->setLabel($optionData->name);
      $this->appendOption($optionBlock);
    }
  }

}