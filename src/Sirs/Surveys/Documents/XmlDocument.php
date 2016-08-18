<?php

namespace Sirs\Surveys\Documents;

use Sirs\Surveys\XmlValidator;

abstract class XmlDocument
{
    public function __construct($xml = null)
    {
        if($xml){
            $compiledXml = $this->compile($xml);
            $simpleXmlElement = $this->getSimpleXmlElement($compiledXml);
            $this->parse($simpleXmlElement);
        }
    }

    protected function compile($xml){

        if( is_string($xml) ){
            $doc = new \SimpleXMLElement($xml);
        }elseif($xml instanceof \SimpleXMLElement){
            $doc = $xml;
        }else{
            throw new \Exception('XML String of SimpleXMLElement expected.');
        } 

        $docDom = dom_import_simplexml($doc);
        $includes = $docDom->getElementsByTagName('include');
        while($includes->length > 0){
            $include = $includes->item(0);
            $source = config('surveys.surveysPath').'/'.$include->getAttribute('source');
            if (!file_exists($source)) {
                throw new \Exception('file '.$source.' not found.', 404);
            }
            $simpleXmlFile = simplexml_load_file($source);
            if (!$simpleXmlFile) {
                throw new \Exception('Error when parsing '.$source);
            }
            $includeDom = dom_import_simplexml($simpleXmlFile);
            $nodeImport = $include->ownerDocument->importNode($includeDom, TRUE);

            if(!$include->parentNode->replaceChild($nodeImport, $include)){
                throw \Exception('wtf');
            }
        }

        $compiled = $doc->asXML();
        return $compiled;

    }

    abstract public function parse(\SimpleXMLElement $simpleXmlElement);

    public function validate()
    {
        return true;
    }

    // public function validate(\SimpleXMLElement $simpleXmlElement)
    // {
    //     return $this->validateXmlElement($simpleXmlElement);
    // }

    public function validateXmlElement(\SimpleXMLElement $element)
    {
        $validator = new XmlValidator(__DIR__.'/../survey.xsd');
        $validator->validate($element->asXml());
            // if we get this far it's valid.
        return true;
    }

    public function getAttribute(\SimpleXMLElement $simpleXmlEl, $attribute)
    {
        if( $simpleXmlEl->attributes()->{$attribute} ){
            return $simpleXmlEl->attributes()->{$attribute}->__toString();
        }
    }

    /**
     * sets the survey XMLElement
     *
     * @return $this
     * @param mixed $surveyXMl
     **/
    function getSimpleXmlElement($xml)
    {
        if( is_string($xml) ){
            $simpleXmlElement = new \SimpleXMLElement($xml);
        }elseif($xml instanceof \SimpleXMLElement){
            $simpleXmlElement = $xml;
        }else{
            throw new \Exception('String or SimpleXMLElement expected.');
        } 
        return $simpleXmlElement;
    }

    public function __get($property)
    {
        $getterMethod = 'get'.ucfirst($property);
        if( method_exists($this, $getterMethod) ){
            return $this->{$getterMethod}();
        }
        return $this->{$property};
    }

    public function __set($property, $value)
    {
        $setterMethod = 'set'.ucfirst($property);
        if( method_exists($this, $setterMethod) ){
            return $this->{$setterMethod}($value);
        }
        return $this->{$property} = $value;
    }


}
