<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Contracts\HasOptionsInterface;
use Sirs\Surveys\Documents\Blocks\OptionBlock;
use Sirs\Surveys\HasOptionsTrait;
use Sirs\Surveys\Variable;

class MultipleChoiceQuestion extends QuestionBlock implements HasOptionsInterface
{
    use HasOptionsTrait;
    
    // protected $options;
    protected $numSelectable;
    protected $defaultSingleTemplate;
    protected $defaultMultiTemplate;

    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultSingleTemplate = 'questions.multiple_choice.radio_group';
        $this->defaultMultiTemplate = 'questions.multiple_choice.checkbox_group';
        $this->defaultTemplate = $this->defaultSingleTemplate;
        $this->defaultDataFormat = 'int';
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        // do this first so refused option is last
        parent::parse($simpleXmlElement);
        $this->setNumSelectable($this->getAttribute($simpleXmlElement, 'num-selectable'));

        $this->parseOptions($simpleXmlElement);
        $this->orderOptions();
    }

    /**
     * Make sure refused option is last if option 
     * @return void
     */
    public function orderOptions()
    {
      if ($this->refusable) {
        $refusedIdx = 0;
        foreach($this->options as $idx => $option){
          if ($option->value == -77) { $refusedIdx = $idx; }
        }
        $refusedOption = array_splice($this->options, $refusedIdx, 1)[0];
        $this->appendOption($refusedOption);
      }
    }

    public function setNumSelectable($number = null)
    {
        $this->numSelectable = ($number) ? $number : 1;
        return $this;
    }

    public function getNumSelectable()
    {
        return $this->numSelectable;
    }

    public function getTemplate()
    {
      $this->defaultTemplate = ($this->getNumSelectable() > 1) ? $this->defaultMultiTemplate : $this->defaultSingleTemplate;
      return parent::getTemplate();
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
        'questionText'=>$this->getQuestionText(),
        'options'=>$this->getOptions(),
      ];
    }

    public function setRefusable($value)
    {
      $this->refusable = ($value) ? true : false;
      if( $this->refusable ){
        $refusedOption = new OptionBlock('refused');
        $refusedOption->setName('refused');
        $refusedOption->setValue(-77);
        $refusedOption->setLabel($this->refuseLabel);
        $refusedOption->setClass('exclusive');
        $this->appendOption($refusedOption);
      }
      return $this;
    }

    /**
     * overrides parent if numSelectable !== 1 to return all option names.
     * @return array
     */
    public function getVariables()
    {
      if( $this->numSelectable == 1 ){
        return parent::getVariables();
      }
      $varNames = [];
      foreach( $this->getOptions() as $idx => $option ){
        $varNames[] = new Variable($option->getName(), 'tinyint');
      }
      return $varNames;
    }

    public function setValidationRules($value){

      if($this->numSelectable == 1 ){
        return parent::setValidationRules($value);
      }else{
        // set based on validation-rules attribute
        if(is_null($value)) return;
        if( is_string($value) ){
          $value = explode('|', $value);
        }
        foreach($this->options as $option){
          if(!isset($this->validationRules[$option->name])){
            $this->validationRules[$option->name] = [];
          }
          $this->validationRules[$option->name][] = $value;
        }
      }
    }

    public function getLaravelValidationArray(){

      if ($this->numSelectable == 1) {
        return parent::getLaravelValidationArray();
      }else{
        $valRules = ($this->getValidationRules()) ? $this->getValidationRules() : [];
        $rules = [];
        foreach($valRules as $optionName => $optionRules){
          $rules[$optionName] = implode('|', $optionRules);
        }
        return $rules;
      }
    }

    public function getValidationRules()
    {
      if ($this->numSelectable == 1) {
        parent::setValidationRules();
      }else{
        $optionNames = $this->getOptionNames();
        foreach($this->options as $option)
        {
          $this->validationRules[$option->name] = [];
          if( $this->required ){
            $others = $optionNames;
            array_splice($others, array_search($option->name, $others), 1);
            $this->validationRules[$option->name][] = 'required_without_all:'.implode(',',$others);
          }
          switch ($this->dataFormat) {
            case 'int':
            case 'tinyint':
            case 'mediumint':
            case 'bigint':
              $this->validationRules[$option->name][] = 'integer';
              break;
            case 'float':
            case 'double':
            case 'decimal':
              $this->validationRules[$option->name][] = 'numeric';
              break;
            case 'date':
            case 'time':
              $this->validationRules[$option->name][] = 'date';
              break;
            case 'year':
              $this->validationRules[$option->name][] = 'regex:\d\d\d\d';
            default:
              break;
          }
        }
      }
      return $this->validationRules;
    }

}