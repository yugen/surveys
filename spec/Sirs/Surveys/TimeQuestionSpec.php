<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TimeQuestionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\TimeQuestion');
    }

    function it_should_set_and_get_its_min()
    {
      $testVal = '0';
      $this->setMin($testVal);
      $this->getMin()->shouldBe($testVal);
    }

    function it_should_set_and_get_its_max()
    {
      $testVal = '10';
      $this->setMax($testVal);
      $this->getMax()->shouldBe($testVal);
    }

}
