<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateQuestionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\DateQuestion');
    }

    function it_should_set_and_get_its_min()
    {
      $testVal = '1900-01-01';
      $this->setMin($testVal);
      $this->getMin()->shouldBe($testVal);
    }

    function it_should_set_and_get_its_max()
    {
      $testVal = '1900-01-01';
      $this->setMax($testVal);
      $this->getMax()->shouldBe($testVal);
    }

}
