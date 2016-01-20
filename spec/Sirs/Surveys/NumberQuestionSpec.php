<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NumberQuestionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\NumberQuestion');
    }

    function it_should_set_and_get_its_min()
    {
      $testVal = '12:00:00';
      $this->setMin($testVal);
      $this->getMin()->shouldBe($testVal);
    }

    function it_should_set_and_get_its_max()
    {
      $testVal = '12:00:00';
      $this->setMax($testVal);
      $this->getMax()->shouldBe($testVal);
    }
}
