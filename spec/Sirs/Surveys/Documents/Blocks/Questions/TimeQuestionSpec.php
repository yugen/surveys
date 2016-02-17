<?php

namespace spec\Sirs\Surveys\Documents\Blocks\Questions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TimeQuestionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Documents\Blocks\Questions\TimeQuestion');
    }

    function it_should_set_and_get_its_min()
    {
      $testVal = '10:00';
      $this->setMin($testVal);
      $this->getMin()->shouldBe($testVal);
    }

    function it_should_set_and_get_its_max()
    {
      $testVal = '10:00';
      $this->setMax($testVal);
      $this->getMax()->shouldBe($testVal);
    }

    function it_validates_a_boundary()
    {
      $this->boundaryIsValid(1)->shouldBe(false);
      $this->boundaryIsValid('beans')->shouldBe(false);
      $this->boundaryIsValid('10:00')->shouldBe(true);
    }

    function it_throws_an_invalid_argument_exception_when_max_or_min_invalid()
    {
      $this->shouldThrow('\InvalidArgumentException')->duringSetMin('beans');
      $this->shouldThrow('\InvalidArgumentException')->duringSetMin([1,2,3]);
    }

    function it_gets_its_validation_string()
    {
      $this->getValidationString()->shouldBe('');
      
      $this->setMin('12:00:00');
      $this->getValidationString()->shouldBe('after:12:00:00');

      $this->setMax('17:00:00');
      $this->getValidationString()->shouldBe('after:12:00:00|before:17:00:00');

      $this->setMin(null);
      $this->getValidationString()->shouldBe('before:17:00:00');

    }

}
