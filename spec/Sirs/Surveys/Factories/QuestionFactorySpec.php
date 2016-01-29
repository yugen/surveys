<?php

namespace spec\Sirs\Surveys\Factories;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QuestionFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Factories\QuestionFactory');
    }

    function it_creates_a_default_question()
    {
      $el = new \SimpleXMLElement('<question name="test"><questionText>What is the color?</questionText></question>');
      $this->create($el)->shouldHaveType('Sirs\Surveys\Documents\Blocks\Questions\QuestionBlock');
    }

    function it_creates_a_date_question_given_a_date()
    {
      $el = new \SimpleXMLElement('<date variable-name="test_date" min="2015-01-01" max="2015-02-01"></date>');
      $this->create($el)->shouldHaveType('Sirs\Surveys\Documents\Blocks\Questions\DateQuestion');
    }

    function it_creates_a_time_question_given_a_time()
    {
      $el = new \SimpleXMLElement('<time variable-name="test_time" min="01:00:00" max="12:00:00"></time>');
      $this->create($el)->shouldHaveType('Sirs\Surveys\Documents\Blocks\Questions\TimeQuestion');
    }
    
    function it_creates_a_number_question_given_a_number()
    {
      $el = new \SimpleXMLElement('<number variable-name="test_time" min="1" max="3"></number>');
      $this->create($el)->shouldHaveType('Sirs\Surveys\Documents\Blocks\Questions\NumberQuestion');
    }
    
    function it_creates_a_multiple_choice_question_given_a_multiple_choice()
    {
      $el = new \SimpleXMLElement('<multiple-choice variable-name="test_time" num-selectable="1"></multiple-choice>');
      $this->create($el)->shouldHaveType('Sirs\Surveys\Documents\Blocks\Questions\MultipleChoiceQuestion');
    }
}
