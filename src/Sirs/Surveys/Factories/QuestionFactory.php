<?php

namespace Sirs\Surveys\Factories;

use Sirs\Surveys\Documents\Blocks\Questions\DateQuestion;
use Sirs\Surveys\Documents\Blocks\Questions\DurationQuestion;
use Sirs\Surveys\Documents\Blocks\Questions\MultipleChoiceQuestion;
use Sirs\Surveys\Documents\Blocks\Questions\NumberQuestion;
use Sirs\Surveys\Documents\Blocks\Questions\NumericScaleQuestion;
use Sirs\Surveys\Documents\Blocks\Questions\QuestionBlock;
use Sirs\Surveys\Documents\Blocks\Questions\TimeQuestion;


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
        'multiple-choice' => MultipleChoiceQuestion::class,
        'numeric-scale' => NumericScaleQuestion::class,
        'duration' => DurationQuestion::class,
      ];
    }


    public function create($xmlElement)
    {
      $questionClass = $this->tagToClassMap[$xmlElement->getName()];
      return new $questionClass($xmlElement);
    }
}
