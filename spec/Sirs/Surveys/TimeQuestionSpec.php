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
}
