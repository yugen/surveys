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
      return ($this->refuseLabel) ? $this->refuseLabel : config('surveys.refuseLabel', 'Refused');
    }

    public function getValidationRules()
    {
      // set rules baded datatype and required attribute
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

    public function getLaravelValidationArray()
    {
      return [$this->name=>$this->getValidationString()];
    }

    public function setValidationRules($value)
    {
      // set based on validation-rules attribute
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

    public function hasOptions()
    {
      return (isset($this->options));
    }

    public function __get($property){
      switch ($property) {
        case 'hasOptions':
          return $this->hasOptions;
          break;
        
        default:
          return parent::__get($property);
          break;
      }
    }

}

  /**
   * gets reporting data for a given question's responses
   *
   * @return collection
   * @author SIRS
   **/
  public function getReport($responses)
  {

    // getting report Types based on data format
    $reportTypes = array();

    switch ($this->dataFormat) {
      case 'int':
      case 'tinyint':
      case 'mediumint':
      case 'bigint':
      case 'float':
      case 'double':
      case 'decimal':
        $reportTypes[] = 'mean';
        $reportTypes[] = 'median';
        $reportTypes[] = 'mode';
        $reportTypes[] = 'range';
        break;
      case 'date':
      case 'time':
      case 'year':
        $reportTypes[] = 'median';
        $reportTypes[] = 'mode';
        $reportTypes[] = 'range';
        break;
      default:
        break;
    }

    // gathering raw data for this question, getting counts
    $raw = $this->getRawData($responses);
    $counts = $this->getDataCounts($raw);
    $answered = $counts['answered'];

    // setting up report
    $report = array();
    $report['total'] = $counts['totalCount'];
    $report['answered'] = $counts['answeredCount'];
    $report['unanswered'] = $counts['unansweredCount'];

    // if question has options, getting extra data for options
    if( isset( $this->options ) ){
      $report['options'] = $this->getOptionsData($answered);
      $report['vis'] = $this->getJSONForVis( $report['options'] );
    }

    // getting reports
    if( $counts['answeredCount'] > 0 ){

      foreach ( $reportTypes as $type ) {
        $method = 'get'.ucfirst($type);
        $report[$type] = $this->$method($answered);

      }
    }
    return collect($report);
  }

  /**
   * gets the mean for a given set of data
   *
   * @return float
   * @author SIRS
   **/
  public function getMean($data)
  {
    $sum = array_sum( $data );
    $count = count( $data );
    $mean = $sum / $count;
    return $mean;
  }

  /**
   * gets the median for a given set of data
   *
   * @return number
   * @author SIRS
   **/
  public function getMedian($data)
  {
    sort( $data );
    $a = array_values( $data );
    $count = count( $a ); 
    $middle = floor( ( $count - 1 ) / 2 ); 
    if( $count % 2 ) { 
        $median = $a[ $middle ];
    } else { 
        $low = $a[$middle];
        $high = $a[$middle + 1];
        $median = ( ( $low + $high ) / 2 );
    }
    return $median;
  }

  /**
   * gets the mode for a given set of data
   *
   * @return number
   * @author SIRS
   **/
  public function getMode($data)
  {
    $values = array();
    foreach( $data as $a ){
      if( !array_key_exists( $a, $values ) ){
        $values[$a] = 0;
      }
      $values[$a] += 1;
    }
    arsort($values);
    reset($values);
    $mode = key($values);
    return $mode;
  }

  /**
   * gets the range for a given set of data
   *
   * @return array
   * @author SIRS
   **/
  public function getRange($data)
  {
    asort( $data );
    $min = array_values( $data )[0];
    arsort( $data );
    $max = array_values( $data )[0];
    return ["min" => $min, "max" => $max];
  }

  /**
   * returns an array of the raw data of all responses for a given question
   *
   * @return array
   * @author SIRS
   **/
  public function getRawData($responses)
  {
    $raw = array();
    foreach( $responses as $response ){
        foreach ($this->getVariables() as $var) {
          $raw[] = $response->{$var->name};
        }
      
    }
    return $raw;
  }

  /**
   * gets the data counts for raw data
   *
   * @return array
   * @author SIRS
   **/
  public function getDataCounts($raw)
  {
    $answered = array();
    $totalCount = 0;
    $answeredCount = 0;
    $unansweredCount = 0;

    foreach ($raw as $data) {
      if( is_null( $data ) ){
        $unansweredCount += 1;
      }else{
        $answeredCount += 1;
        $answered[] = $data;
      }
      $totalCount += 1;
    }

    $arr = [
      'answered' => $answered,
      'totalCount' => $totalCount,
      'answeredCount' => $answeredCount,
      'unansweredCount' => $unansweredCount,
    ];
    return $arr;
  }

  /**
   * gets data per option for questions with option
   *
   * @return collection
   * @author sirs
   **/
  public function getOptionsData($data)
  {
    $options = array();
    foreach ($this->options as $option) {
      if( !array_key_exists( $option->value, $options ) ){
        $options[$option->value] = array();
        $options[$option->value]['value'] = $option->value;
        $options[$option->value]['label'] = $option->label;
        $options[$option->value]['count'] = 0;
      }
    }

    foreach( $data as $ans ){
      if( array_key_exists( $ans, $options ) ){
        $options[$ans]['count'] +=1;
      }
    }

  return collect( $options );
  }

   /**
   * returns a JSON obect of option data for nvd3 visualizations
   *
   * @return object
   * @author sirs
   **/
  public function getJSONForVis($options){

    $jsonArray = array();
    $jsonArray['key'] = $this->variableName;
    $jsonArray['values'] = array();
    foreach ( $options as $option ) {
      $val = array();
      $val['value'] = $option['count'];
      $val['label'] = $option['value'];
      $jsonArray['values'][] = $val;
    }

    return collect($jsonArray)->toJSON();
  }
