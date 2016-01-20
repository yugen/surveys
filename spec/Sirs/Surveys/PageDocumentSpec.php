<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PageDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\PageDocument');
    }

    function it_should_implement_PageDocumentInterface()
    {
      $this->shouldImplement('Sirs\Surveys\Contracts\PageDocumentInterface');
    }

    function it_should_implement_ContainerInterface()
    {
      $this->shouldImplement('Sirs\Surveys\Contracts\ContainerInterface');
    }

    function it_should_set_and_get_its_source()
    {
      $this->setSource('beans.txt');
      $this->getSource()->shouldBe('beans.txt');
    }

    function it_should_set_and_get_its_title()
    {
      $this->setTitle('Test Title');
      $this->getTitle()->shouldBe('Test Title');
    }

    function it_should_return_itself_unless_method_is_a_getter()
    {
      $self = $this->setSource('beans.txt');
      $self->shouldHaveType('Sirs\Surveys\PageDocument');

      $self = $this->setTitle('Test Title');
      $self->shouldHaveType('Sirs\Surveys\PageDocument');
    }
}