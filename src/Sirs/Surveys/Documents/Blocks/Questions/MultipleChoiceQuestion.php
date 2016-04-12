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

    public function parse()
    {
        $this->parseOptions();
        $this->setNumSelectable($this->getAttribute($this->xmlElement, 'num-selectable'));
        parent::parse();
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
        $refusedOption->setValue(-77);
        $refusedOption->setLabel('Refused');
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
}
