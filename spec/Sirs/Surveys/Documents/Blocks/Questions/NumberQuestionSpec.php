<?php

namespace spec\Sirs\Surveys\Documents\Blocks\Questions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NumberQuestionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Documents\Blocks\Questions\NumberQuestion');
    }

    function it_should_set_and_get_its_min()
    {
      $testVal = 1;
      $this->setMin($testVal);
      $this->getMin()->shouldBe($testVal);
    }

    function it_should_set_and_get_its_max()
    {
      $testVal = 10;
      $this->setMax($testVal);
      $this->getMax()->shouldBe($testVal);
    }

    function it_validates_a_boundary()
    {
      $this->boundaryIsValid(1)->shouldBe(true);
      $this->boundaryIsValid('beans')->shouldBe(false);
    }

    function it_throws_an_invalid_argument_exception_when_max_or_min_invalid()
    {
      $this->shouldThrow('\InvalidArgumentException')->duringSetMin('beans');
      $this->shouldThrow('\InvalidArgumentException')->duringSetMin([1,2,3]);
    }
}
