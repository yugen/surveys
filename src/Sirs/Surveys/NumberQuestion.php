<?php

namespace Sirs\Surveys;

class NumberQuestion extends QuestionBlock
{
    protected $min;
    protected $max;

    public function __construct($xml = null)
    {
        $this->defaultTemplate = 'questions/number/default.blade.php';
        $this->defaultDataFormat = 'int';
        parent::__construct($xml);
    }

    public function setMin($argument1)
    {
        $this->min = $argument1;
        return $this;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function setMax($argument1)
    {
        $this->max = $argument1;
        return $this;
    }

    public function getMax()
    {
        return $this->max;
    }
}
