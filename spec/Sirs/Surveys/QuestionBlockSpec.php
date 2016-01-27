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
      $this->setName('test');
      $this->getName()->shouldBe('test');
    }

    function it_should_have_a_default_data_format()
    {
      $this->getDataFormat()->shouldBe('varchar');
    }

    function it_should_have_a_default_template()
    {
      $this->getTemplate()->shouldBe('questions.text.default_text');
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
      $this->setName('question');
      $this->setQuestionText('What is this?');
      $this->getDataDefinition()->shouldBe([
        'variableName'=>'question',
        'dataFormat'=>'varchar',
        'questionText'=>'What is this?'
      ]);
    }

    function it_should_set_and_get_required()
    {
      $this->setRequired(true);
      $this->getRequired()->shouldBe(true);
    }

    function it_should_render_itself()
    {
      $this->name = 'Beans';
      $this->required = true;
      $this->class = 'monkey';
      $this->id = 'beans';
      $this->questionText = 'What is your favorite color';
      $this->dataFormat = 'varchar';
      $this->placeholder = 'beans!';
      $template = <<<TPL
<div 
  class="form-group question-block monkey
  @if( count(\$response->errors['Beans']) > 0 ) has-error @endif" 
  id=""
>
    <div class="question-text">What is your favorite color</div>
  
  @if('\$response->errors['Beans']['required'])<div class="error">This question is required</div>@endif
  <div class="question-answers">
    <input type="text" name="Beans" placeholder=" required />
  </div>
</div>

TPL;
      $view = $this->renderer->render($this->defaultTemplate, ['renderable'=>$this])->shouldBe($template);
    }
}
