<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SurveyDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\SurveyDocument');
        $this->shouldImplement('Sirs\Surveys\Contracts\SurveyDocumentInterface');
    }

}
