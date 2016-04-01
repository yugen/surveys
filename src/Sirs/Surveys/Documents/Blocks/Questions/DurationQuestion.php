<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Documents\Blocks\Questions\NumberQuestion;

class DurationQuestion extends NumberQuestion
{
    protected $unit;
    protected $precision;

    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultTemplate = 'questions.number.duration';
        $this->defaultDataFormat = 'int';
    }

    public function parse()
    {
      parent::parse();
      $this->setUnit($this->getAttribute($this->xmlElement, 'unit'));
      $this->setPrecision($this->getAttribute($this->xmlElement, 'precision'));
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
