<?php

namespace Sirs\Surveys\Parsers;

abstract class Parser
{

    abstract public function parse(\SimpleXMLElement $simpleXmlElement);

    public function getAttribute(\SimpleXMLElement $simpleXmlEl, $attribute)
    {
        if( $simpleXmlEl->attributes()->{$attribute} ){
            return $simpleXmlEl->attributes()->{$attribute}->__toString();
        }
    }

}
