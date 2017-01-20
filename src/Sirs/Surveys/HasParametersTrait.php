<?php 

namespace Sirs\Surveys;

trait HasParametersTrait
{

  public function parseParameters(\SimpleXMLElement $simpleXmlElement){
      if( $simpleXmlElement->parameter ){
          foreach( $simpleXmlElement->parameter as $param ){
            $this->addParameter($this->getAttribute($param, 'name'), $this->getAttribute($param, 'value'));
          }
      }
  }

  public function setParameters(array $parameters)
  {
      $this->parameters = $parameters;
      return $this;
  }

  public function getParameters()
  {
      return (isset($this->parameters)) ? $this->parameters : [];
  }

  public function addParameter($name, $value)
  {
    if(!isset($this->parameters)) $this->parameters = [];
    $this->parameters[$name] = $value;
  }

  public function removeParameter($name)
  {
    if(isset($this->parameters) && isset($this->parameters[$name]))
        unset($this->parameters[$name]);    
  }

  public function mergeParameters($parameters)
  {
        $this->parameters = array_merge($parameters, $this->getParameters());
  }

  static public function createWithParameters($xmlElement, $parameters)
  {
    $class = get_called_class();
    $obj = new $class();
    $obj->setParameters($parameters);
    $obj->parse($xmlElement); // calls the parse method
    return $obj;
  }

}