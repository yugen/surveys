<?php

namespace Sirs\Surveys\Documents\Blocks;

/**
* Option Block Class
*/
class OptionBlock extends RenderableBlock
{
  protected $value;
  protected $selected;
  protected $label;
  protected $name;
  protected $variableName;
  protected $show = null;
  protected $hide = null;

  public function __construct($variableName, $xml = null )
  {
    $this->variableName = $variableName;
    parent::__construct($xml);
    $this->defaultTemplate = 'options.default_radio_option';
  }



  public function parse()
  {
      parent::parse();
      if( $this->getAttribute($this->xmlElement, 'name') ){
        $this->setName($this->getAttribute($this->xmlElement, 'name'));
      }
      $this->setLabel($this->xmlElement->label[0]->__toString());
      $this->setValue($this->xmlElement->value[0]->__toString());
      $this->setSelected($this->getAttribute($this->xmlElement, 'selected'));
      $this->setName($this->getAttribute($this->xmlElement, 'name'));
      $this->setShow($this->getAttribute($this->xmlElement, 'show'));
      $this->setHide($this->getAttribute($this->xmlElement, 'hide'));
  }

  public function setLabel($label)
  {
      $this->label = $label;
      return $this;
  }

  public function getLabel()
  {
    return $this->label;
  }

  public function setValue($value)
  {
      $this->value = $value;
      return $this;
  }

  public function getValue()
  {
      return $this->value;
  }

  public function getSelected()
  {
    return (bool)$this->selected;
  }

  public function setSelected($selected)
  {
      $this->selected = $selected;
      return $this;
  }

  public function setName($name){
    $this->name = $name;
    return $this;
  }

  public function getName()
  {
    return ($this->name) ? $this->name : $this->variableName;
  }

  public function getId()
  {
    return ($this->id) ? $this->id : $this->name.'-'.$this->value ;
  }

  public function render($context){
    $output = parent::render($context);
    return $output;
  }

  public function setShow($show)
  {
    $this->show = $show;
    return $this;
  }

  public function getShow(){
    return $this->show;
  }

  public function setHide($hide)
  {
    $this->hide = $hide;
    return $this;
  }

  public function getHide(){
    return $this->hide;
  }



}