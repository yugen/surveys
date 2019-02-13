<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Exceptions\InvalidAttributeValueException;
use Sirs\Surveys\Documents\Blocks\Questions\NumericScaleQuestion;

class YearQuestion extends NumericScaleQuestion
{
    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultTemplate = config('surveys.default_templates.year', 'questions.multiple_choice.select');
        $this->defaultDataFormat = 'int';
    }

    public function setMin($min)
    {
        try {
            return parent::setMin($min);
        } catch (InvalidAttributeValueException $e) {
            $this->min = 1900;
            return $this;
        }
    }

    public function setMax($max)
    {
        try {
            return parent::setMax($max);
        } catch (InvalidAttributeValueException $e) {
            $this->max = 2100;
            return $this;
        }
    }

    public function boundaryIsValid($bound)
    {
        return (!is_null($bound));
    }
}
