<?php

namespace Sirs\Surveys;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\Documents\Blocks\Questions\QuestionBlock;
use Sirs\Surveys\Factories\QuestionFactory;

trait HasQuestionsTrait{

  public function getQuestions()
  {
    $questions = [];
    foreach ($this->getContents() as $idx => $block) {
      if ($block instanceof QuestionBlock) {
        $questions[] = $block;
      }elseif($block instanceof ContainerBlock) {
        $questions = array_merge($questions, $block->getQuestions());
      }
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