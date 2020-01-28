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
    protected $exclusive = null;

    public function __construct($variableName, $xml = null)
    {
        $this->variableName = $variableName;
        parent::__construct($xml);
        $this->defaultTemplate = 'options.default_radio_option';
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        parent::parse($simpleXmlElement);
        if ($this->getAttribute($simpleXmlElement, 'name')) {
            $this->setName($this->getAttribute($simpleXmlElement, 'name'));
        }
        $this->setLabel($simpleXmlElement->label[0]->__toString());
        $this->setValue($simpleXmlElement->value[0]->__toString());
        $this->setSelected($this->getAttribute($simpleXmlElement, 'selected'));
        $this->setName($this->getAttribute($simpleXmlElement, 'name'));
        $this->setShow($this->getAttribute($simpleXmlElement, 'show'));
        $this->setHide($this->getAttribute($simpleXmlElement, 'hide'));
        $this->setExclusive($this->getAttribute($simpleXmlElement, 'exclusive'));
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    public function labelIsSet()
    {
        return $this->label != '';
    }

    public function getLabel()
    {
        return ($this->labelIsSet()) ? trim($this->label) : $this->value;
    }

    public function getCompiledLabel($context)
    {
        return $this->bladeCompile($this->label, $context);
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

    public function setName($name)
    {
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

    public function render($context)
    {
        $output = parent::render($context);

        return $output;
    }

    public function setShow($show)
    {
        $this->show = $show;

        return $this;
    }

    public function getShow()
    {
        return $this->show;
    }

    public function setHide($hide)
    {
        $this->hide = $hide;

        return $this;
    }

    public function getHide()
    {
        return $this->hide;
    }

    public function setExclusive($value)
    {
        $this->exclusive = $value;
    }

    public function getExclusive()
    {
        return $this->exclusive;
    }
}
