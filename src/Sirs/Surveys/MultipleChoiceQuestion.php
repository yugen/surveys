<?php

namespace Sirs\Surveys;

class MultipleChoiceQuestion extends QuestionBlock
{
    protected $options;
    protected $numSelectable;
    protected $defaultSingleTemplate;
    protected $defaultMultiTemplate;

    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultSingleTemplate = 'questions/multiple_choice/radio_group.blade.php';
        $this->defaultMultiTemplate = 'questions/multiple_choice/checkbox_group.blade.php';
        $this->defaultTemplate = $this->defaultSingleTemplate;
        $this->defaultDataFormat = 'int';
    }

    public function parse()
    {
        parent::parse();
        $this->parseOptions();
        $this->setNumSelectable($this->getAttribute($this->xmlElement, 'num-selectable'));
    }

    public function parseOptions(){
        $options = [];
        if( $this->xmlElement->options->option ){
            foreach( $this->xmlElement->options->option as $option ){
                $options[] = [
                    'label'=>$option->label[0]->__toString(),
                    'value'=>$option->value[0]->__toString()
                ];
            }
            $this->setOptions($options);
        }
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setNumSelectable($number)
    {
        $this->numSelectable = $number;
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


}
