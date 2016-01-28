<?php

namespace Sirs\Surveys\Factories;

use Sirs\Surveys\DateQuestion;
use Sirs\Surveys\MultipleChoiceQuestion;
use Sirs\Surveys\NumberQuestion;
use Sirs\Surveys\QuestionBlock;
use Sirs\Surveys\TimeQuestion;

class QuestionFactory
{

    protected $tagToClassMap;

    public function __construct()
    {
      $this->tagToClassMap = [
        'question' => QuestionBlock::class,
        'date'     => DateQuestion::class,
        'time'     => TimeQuestion::class,
        'number'   => NumberQuestion::class,
        'multiple-choice' => MultipleChoiceQuestion::class
      ];
    }


    public function create($xmlElement)
    {
      $questionClass = $this->tagToClassMap[$xmlElement->getName()];
      return new $questionClass($xmlElement);
    }
}
