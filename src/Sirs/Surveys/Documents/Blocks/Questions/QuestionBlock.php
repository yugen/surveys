<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Contracts\StructuredDataInterface;
use Sirs\Surveys\Documents\Blocks\RenderableBlock;

class QuestionBlock extends RenderableBlock implements StructuredDataInterface
{
  protected $variableName;
  protected $dataFormat;
  protected $questionText;
  protected $defaultDataFormat;
  protected $required = false;
  protected $placeholder;

  public function __construct($xml = null)
  {
    parent::__construct($xml);
    $this->defaultDataFormat = 'varchar';
    $this->defaultTemplate = 'questions.text.default_text';
  }

  public function parse()
  {

    parent::parse();
    $this->setName($this->getAttribute($this->xmlElement, 'name'));
    $this->setQuestionText($this->xmlElement->{'question-text'}[0]);
    $this->setDataFormat($this->getAttribute($this->xmlElement, 'data-format'));
    $this->setRequired($this->getAttribute($this->xmlElement, 'required'));
    $this->setPlaceholder($this->getAttribute($this->xmlElement, 'placeholder'));
  }

  /**
   * sets the variable name for this item
   *
   * @param string $varName
   * @return string
   **/
  public function setName($varName){
    $this->variableName = $varName;
    return $this;
  }

  /**
   * returns the variable name for this item
   *
   * @return string
   **/
  public function getName(){
    return $this->variableName;
  }

  /**
   * sets the datatype for this item
   *
   * @param string
   **/
  function setDataFormat($dataFormat){
    $this->dataFormat = $dataFormat;
    return $this;
  }

  /**
   * returns the datatype for this item
   *
   * @return string
   **/
  function getDataFormat(){
    return ($this->dataFormat) ? $this->dataFormat : $this->defaultDataFormat;
  }

  /**
   * sets the question text used to collect this data
   *
   * @param string $questionText
   * @author 
   **/
  public function setQuestionText($questionText)
  {
    $this->questionText = $questionText;
    return $this;
  }

  /**
   * gets the question text used to collect this data
   *
   * @return void
   * @author 
   **/
  public function getQuestionText()
  {
    return $this->questionText;
  }

  /**
   * returns a data definition for this item
   *
   * @return void
   * @author 
   **/
  public function getDataDefinition(){
    return [
      'variableName'=>$this->getName(),
      'dataFormat'=>$this->getDataFormat(),
      'questionText'=>$this->getQuestionText()
    ];
  }

    public function setRequired($required)
    {
        $this->required = ($required !== null) ? $required : false;
        return $this;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function setPlaceholder($placeholder)
    {
      $this->placeholder = ($placeholder !== null) ? $placeholder : null;
      return $this;
    }

    public function getPlaceholder()
    {
      return $this->placeholder;
    }

}
