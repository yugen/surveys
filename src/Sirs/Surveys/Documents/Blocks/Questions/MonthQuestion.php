<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Documents\Blocks\OptionBlock;
use Sirs\Surveys\Contracts\HasOptionsInterface;
use Sirs\Surveys\Exceptions\InvalidAttributeValueException;

class MonthQuestion extends NumericScaleQuestion
{
    protected $monthMap = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December',
    ];

    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultTemplate = config('surveys.default_templates.year', 'questions.multiple_choice.select');
        $this->defaultDataFormat = 'int';
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        parent::parse($simpleXmlElement);
        $this->setInterval($this->getAttribute($simpleXmlElement, 'interval'));
        $this->parseLegend($simpleXmlElement);

        $this->setOptions([]);

        foreach (range($this->min, $this->max, $this->getInterval()) as $num) {
            $option = new OptionBlock($this->monthMap[$num]);
            $option->setLabel($this->monthMap[$num]);
            $option->setValue($num);
            $this->appendOption($option);
        }
        if ($this->refusable) {
            $refusable = new OptionBlock('refused');
            $refusable->setLabel('Refused');
            $refusable->setValue(config('surveys.refusedValue', -77));
            $refusable->setClass('hidden');
            $this->appendOption($refusable);
        }
    }

    public function setMin($min)
    {
        try {
            return parent::setMin($min);
        } catch (InvalidAttributeValueException $e) {
            $this->min = 1;
            return $this;
        }
    }

    public function setMax($max)
    {
        try {
            return parent::setMax($max);
        } catch (InvalidAttributeValueException $e) {
            $this->max = 12;
            return $this;
        }
    }

    public function boundaryIsValid($bound)
    {
        return (!is_null($bound) && $bound > 0 && $bound < 13);
    }
}
