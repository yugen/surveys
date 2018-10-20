<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

class DurationQuestion extends NumberQuestion
{
    protected $unit;
    protected $precision;

    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultTemplate = config('surveys.default_templates.duration', 'questions.number.duration');
        $this->defaultDataFormat = 'int';
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        parent::parse($simpleXmlElement);
        $this->setUnit($this->getAttribute($simpleXmlElement, 'unit'));
        $this->setPrecision($this->getAttribute($simpleXmlElement, 'precision'));
    }

    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    public function getUnit()
    {
        return ($this->unit) ? $this->unit : 'minute';
    }

    public function setPrecision($unit)
    {
        $this->precision = $unit;
    }

    public function getPrecision()
    {
        return ($this->precision) ? $this->precision : 'minute';
    }
}
