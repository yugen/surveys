<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QuestionBlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\QuestionBlock');
    }

    function it_should_set_and_get_its_variable_name()
    {
      $this->setVariableName('test');
      $this->getVariableName()->shouldBe('test');
    }

    function it_should_have_a_default_data_format()
    {
      $this->getDataFormat()->shouldBe('varchar');
    }

    function it_should_have_a_default_template()
    {
      $this->getTemplate()->shouldBe('questions/default.blade.php');
    }

    function it_should_set_and_get_its_data_format()
    { 
      $this->getDataFormat()->shouldBe('varchar');

      $this->setDataFormat('int');
      $this->getDataFormat()->shouldBe('int');
    }
    function it_should_set_and_get_its_question_text()
    {
      $this->setQuestionText('test');
      $this->getQuestionText()->shouldBe('test');
    }

    function it_should_get_a_data_definition()
    {
      $this->setVariableName('question');
      $this->setQuestionText('What is this?');
      $this->getDataDefinition()->shouldBe([
        'variableName'=>'question',
        'dataFormat'=>'varchar',
        'questionText'=>'What is this?'
      ]);
    }
}
