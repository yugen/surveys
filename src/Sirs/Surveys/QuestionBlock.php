<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\StructuredDataInterface;

class QuestionBlock extends RenderableBlock implements StructuredDataInterface
{
  protected $variableName;
  protected $dataFormat;
  protected $questionText;

  public function parse()
  {
    parent::parse();
    $this->setVariableName($this->getAttribute($this->xmlElement, 'variable-name'));
    $this->setQuestionText($this->xmlElement->{'question-text'}[0]);
    $this->setDataFormat($this->getAttribute($this->xmlElement, 'data-format'));
  }

  /**
   * sets the variable name for this item
   *
   * @param string $varName
   * @return string
   **/
  public function setVariableName($varName){
    $this->variableName = $varName;
    return $this;
  }

  /**
   * returns the variable name for this item
   *
   * @return string
   **/
  public function getVariableName(){
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
    return ($this->dataFormat) ? $this->dataFormat : 'varchar';
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
      'variableName'=>$this->getVariableName(),
      'dataFormat'=>$this->getDataFormat(),
      'questionText'=>$this->getQuestionText()
    ];
  }

}
