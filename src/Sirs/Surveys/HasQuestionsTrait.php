<?php

namespace Sirs\Surveys;
use Sirs\Surveys\Factories\QuestionFactory;

trait HasQuestionsTrait{

  public function getQuestions()
  {
    $questionFactory = new QuestionFactory();
    $questions = [];
    $questionNodes = $this->xmlElement->xpath('.//question|.//date|.//time|.//number|.//text|.//upload|.//multiple-choice|.//numeric-scale|.//duration');
    foreach( $questionNodes as $el ){
      $questions[] = $questionFactory->create($el);
    }
    return $questions;
  }

  /**
   * gets variable names for all questions in this container
   *
   * @return array
   **/
  public function getVariables()
  {
    $varNames = [];
    foreach( $this->getQuestions() as $question ){
      $varNames = array_merge($varNames, $question->getVariables());
    }
    return $varNames;
  }



}