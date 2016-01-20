<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MultipleChoiceQuestionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\MultipleChoiceQuestion');
    }

    function it_should_set_and_get_its_options()
    {
      $options = [1=>'beans', 2=>'Monkeys', -99=>'No Answer'];
      $this->setOptions($options);
      $this->getOptions()->shouldBe($options);
    }

    function it_should_set_and_get_its_num_selectable()
    {
      $numSelectable = 3;
      $this->setNumSelectable($numSelectable);
      $this->getNumSelectable()->shouldBe($numSelectable);
    }

    function it_should_set_its_default_template_depending_on_num_selectable()
    {
      $this->setNumSelectable(1);
      $this->getTemplate()->shouldBe('questions/multiple_choice/radio_group.blade.php');

      $this->setNumSelectable(2);
      $this->getTemplate()->shouldBe('questions/multiple_choice/checkbox_group.blade.php');
    }

    function it_includes_options_in_its_data_definition()
    {
      $test = [1,2,3];
      $this->setOptions($test);
      $this->getDataDefinition()->shouldHaveKeyWithValue('options', $test);
    }

}
