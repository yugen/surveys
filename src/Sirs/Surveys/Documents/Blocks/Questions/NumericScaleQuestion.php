<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Contracts\HasOptionsInterface;
use Sirs\Surveys\Documents\Blocks\OptionBlock;
use Sirs\Surveys\HasOptionsTrait;

class NumericScaleQuestion extends NumberQuestion implements HasOptionsInterface
{
    use HasOptionsTrait;
    public $numSelectable = 1;

    protected $interval;
    protected $legend = [];

    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultTemplate = config('surveys.default_templates.numeric_scale', 'questions.number.numeric_scale');
        $this->defaultDataFormat = 'int';
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        parent::parse($simpleXmlElement);
        $this->setInterval($this->getAttribute($simpleXmlElement, 'interval'));
        $this->parseLegend($simpleXmlElement);

        foreach (range($this->min, $this->max, $this->getInterval()) as $num) {
            $option = new OptionBlock($num);
            $option->setLabel($num);
            $option->setValue($num);
            $this->appendOption($option);
        }
        if ($this->refusable) {
            $refusable = new OptionBlock('refused');
            $refusable->setLabel('Refused');
            $refusable->setValue(-77);
            $refusable->setClass('hidden');
            $this->appendOption($refusable);
        }
    }

    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    public function getInterval()
    {
        return ($this->interval) ? $this->interval : 1;
    }

    public function parseLegend(\SimpleXMLElement $simpleXmlElement)
    {
        if ($simpleXmlElement->legend && $simpleXmlElement->legend->item) {
            foreach ($simpleXmlElement->legend->item as $i) {
                $item = [
                'label'=>$i->label[0]->__toString(),
                'value'=>$i->value[0]->__toString(),
            ];
                $this->appendItem($item);
            }
        }
    }

    public function setLegend(array $items)
    {
        $this->legend = $items;

        return $this;
    }

    public function getLegend()
    {
        return ($this->legend) ? $this->legend : [];
    }

    public function appendItem($item)
    {
        $this->legend[] = $item;
    }

    public function prependItem($item)
    {
        array_unshift($this->legend, $item);
    }
}
