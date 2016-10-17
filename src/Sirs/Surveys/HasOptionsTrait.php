<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Documents\Blocks\OptionBlock;

trait HasOptionsTrait{
    protected $options = [];

    public function parseOptions(\SimpleXMLElement $simpleXmlElement){
            if( !$simpleXmlElement->options->option && !$simpleXmlElement->options->{'data-source'} ){
                // throw new \Exception('No options or data-source found');
            }
            if( $simpleXmlElement->options->{'data-source'} ){
                    $dataSourceEl = $simpleXmlElement->options->{'data-source'};
                    $dataSourceUri = $this->getAttribute($dataSourceEl, 'URI');

                    $pattern = '/\[PARAM:(\w+)\]/';
                    $dataSourceUri = preg_replace_callback('/\[PARAM:(\w+)\]/', function($matches){
                        $params = $this->getParameters();
                        foreach ($matches as $idx => $match) {
                            if ($idx % 2 == 0) continue;
                            if(isset($params[$match])){
                                return $paramValue = $params[$match];
                            }
                        }
                    }, $dataSourceUri);
                    $this->getOptionsFromDataSource($dataSourceUri);
            }
            if( $simpleXmlElement->options->option ){
                    foreach( $simpleXmlElement->options->option as $option ){
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

    protected function getOptionsFromDataSource($dataSourceUri)
    {
        $responseString = file_get_contents(url($dataSourceUri));
        if( $responseString === false ){ throw new \Exception('Failed to got data from '.$dataSourceUri);}
        $sourceData = json_decode($responseString);
        foreach( $sourceData as $idx => $optionData ){
            if ($this->numSelectable > 1) {
                if ($optionData->slug) {
                    $name = $optionData->slug;
                }else{
                    $name = preg_replace('/ /', '_', $optionData->name);
                }
            }else{
                $name = $this->name;
            }
            $optionBlock = new OptionBlock($name);
            $optionBlock->setValue($optionData->id);
            $optionBlock->setLabel($optionData->name);
            $this->appendOption($optionBlock);
        }
    }

    public function getOptionsForResponseValue($responseValue)
    {
        $options = [];
        if ($this->numSelectable > 1) {
        }else{
            foreach($this->options as $option){
                if ($option->value == $responseValue) {
                    $options[] = $option;
                }
            }
        }
        return $options;
    } 

    public function getOptionNames()
    {
        $names = [];
        foreach ($this->options as $option) {
            $names[] = $option->name;
        }
        return $names;
    } 

}
