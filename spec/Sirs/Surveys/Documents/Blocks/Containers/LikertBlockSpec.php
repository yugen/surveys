<?php

namespace spec\Sirs\Surveys\Documents\Blocks\Containers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LikertBlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Documents\Blocks\Containers\LikertBlock');
    }

    function it_should_implement_has_options()
    {
      $this->shouldImplement('Sirs\Surveys\Contracts\HasOptionsInterface');
    }

    function it_sets_and_gets_its_prompt()
    {
      $prompt = 'Answer these questions!';
      $this->setPrompt($prompt);
      $this->getPrompt()->shouldBe($prompt);
    }
}
