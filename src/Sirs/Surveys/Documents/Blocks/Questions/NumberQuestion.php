<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

class NumberQuestion extends BoundedQuestion
{
    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultTemplate = config('surveys.default_templates.number', 'questions.number.number');
        $this->defaultDataFormat = 'int';
    }

    public function boundaryIsValid($bound)
    {
        if (is_scalar($bound)) {
            return (ctype_digit($bound) || preg_match('/^\d*\.?\d*$/', $bound));
        } elseif (is_null($bound)) {
            return true;
        }

        return false;
    }

    public function getValidationRules()
    {
        $validations = parent::getValidationRules();
        $validations[] = 'numeric';

        $min = min($this->min, $this->max);
        $max = max($this->min, $this->max);

        if ($min !== null) {
            if ($this->refusable) {
                $validations[] = 'refusableIntMin:'.$min;
            } else {
                $validations[] = 'intMin:'.$min;
            }
        }

        if ($max !== null) {
            $validations[] = 'intMax:'.$max;
        }

        return $validations;
    }
}
