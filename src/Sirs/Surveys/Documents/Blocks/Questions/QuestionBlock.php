<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Contracts\StructuredDataInterface;
use Sirs\Surveys\Documents\Blocks\RenderableBlock;
use Sirs\Surveys\Variable;

class QuestionBlock extends RenderableBlock implements StructuredDataInterface
{
  protected $variableName;
  protected $dataFormat;
  protected $questionText;
  protected $defaultDataFormat;
  protected $required = false;
  protected $placeholder;
  protected $validationRules = [];
  protected $show = null;
  protected $hide = null;
  protected $refusable = null;
  protected $refuseLabel = null;

  public function __construct($xml = null)
  {
    parent::__construct($xml);
    $this->defaultDataFormat = 'varchar';
    $this->defaultTemplate = 'questions.text.default_text';
  }

  public function parse(\SimpleXMLElement $simpleXmlElement)
  {

    parent::parse($simpleXmlElement);
    $this->setName($this->getAttribute($simpleXmlElement, 'name'));
    $this->setQuestionText((string)$simpleXmlElement->{'question-text'}[0]);
    $this->setDataFormat($this->getAttribute($simpleXmlElement, 'data-format'));
    $this->setRequired($this->getAttribute($simpleXmlElement, 'required'));
    $this->setPlaceholder($this->getAttribute($simpleXmlElement, 'placeholder'));
    $this->setShow($this->getAttribute($simpleXmlElement, 'show'));
    $this->setHide($this->getAttribute($simpleXmlElement, 'hide'));
    $this->setRefuseLabel($this->getAttribute($simpleXmlElement, 'refuse-label'));
    $this->setRefusable($this->getAttribute($simpleXmlElement, 'refusable'));
    $this->setValidationRules($this->getAttribute($simpleXmlElement, 'validation-rules'));
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

  function getCompiledQuestionText($context)
  {
    return $this->bladeCompile($this->questionText, $context);
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
    $this->Hide = $hide;
    return $this;
  }

  public function getHide(){
    return $this->hide;
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

    public function setRefusable($value)
    {
      $this->refusable = ($value) ? true : false;
      return $this;
    }

    public function getRefusable()
    {
      return ($this->refusable) ? true : false;
    }

    public function setRefuseLabel($value)
    {
      $this->refuseLabel = ($value) ? $value : config('surveys.refuseLabel', 'Refused');
      return $this;
    }

    public function getRefuseLabel()
    {
      return ($this->refuseLabel) ? $this->refuseLabel : congif('surveys.refuseLabel', 'Refused');
    }

    public function getValidationRules()
    {
      if( $this->required ){
        $this->validationRules[] = 'required';
      }
      switch ($this->dataFormat) {
        case 'int':
        case 'tinyint':
        case 'mediumint':
        case 'bigint':
          $this->validationRules[] = 'integer';
          break;
        case 'float':
        case 'double':
        case 'decimal':
          $this->validationRules[] = 'numeric';
          break;
        case 'date':
        case 'time':
          $this->validationRules[] = 'date';
          break;
        case 'year':
          $this->validationRules[] = 'regex:\d\d\d\d';
        default:
          break;
      }
      return $this->validationRules;
    }

    public function setValidationRules($value)
    {
      if(is_null($value)) return;
      if( is_string($value) ){
        $value = explode('|', $value);
      }
      $this->validationRules = array_merge($this->validationRules, $value);
    }

    public function getValidationString()
    {
      return implode('|', $this->getValidationRules());
    }

    public function getVariables()
    {
      return [new Variable($this->variableName, $this->getDataFormat())];
    }


}
