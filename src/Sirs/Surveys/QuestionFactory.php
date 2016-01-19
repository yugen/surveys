<?php

namespace Sirs\Surveys;

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
