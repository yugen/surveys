<?php

namespace Sirs\Surveys;
use Sirs\Surveys\Factories\QuestionFactory;

trait HasQuestionsTrait{

  public function getQuestions()
  {
    $questionFactory = new QuestionFactory();
    $questions = [];
    $questionNodes = $this->xmlElement->xpath('.//question|.//date|.//time|.//number|.//text|.//upload|.//multiple-choice');
    foreach( $questionNodes as $el ){
      $questions[] = $questionFactory->create($el);
    }
    return $questions;
  }

}