<?php

namespace Sirs\Surveys\Documents;

abstract class XmlDocument
{
  protected $xmlElement;

  public function __construct($xml = null)
  {
    if($xml){
      $this->setXmlElement($xml);
    }
  }

  abstract public function parse();

  public function validate()
  {
      $doc = new \DOMDocument();
      $doc->loadXML($this->xmlElement->asXml());
      return $doc->schemaValidate('schema/survey.xsd');
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
  function setXmlElement($xml)
  {
    if( is_string($xml) ){
      $this->xmlElement = new \SimpleXMLElement($xml);
    }elseif($xml instanceof \SimpleXMLElement){
      $this->xmlElement = $xml;
    }else{
      throw Exception('String of SimpleXMLElement expected.');
    } 
    $this->parse();
    return $this;
  }

  /**
   * gets survey XMLElement
   *
   * @return string
   **/
  public function getXmlElement()
  {
    return $this->xmlElement;
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