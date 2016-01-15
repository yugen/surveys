<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RenderableBlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\RenderableBlock');
    }

    function it_should_implement_RenderableBlockInterface()
    {
      $this->shouldImplement('Sirs\Surveys\Contracts\RenderableBlockInterface');
    }

    function its_constructor_should_take_its_properties()
    {
      $this->beConstructedWith('$class', '$id', '$template');
      $this->getClass()->shouldBe('$class');
      $this->getId()->shouldBe('$id');
      $this->getTemplate()->shouldBe('$template');
    }

    function it_should_set_and_get_its_template()
    {
      $this->setTemplate('template.blade.php');
      $this->getTemplate()->shouldBe('template.blade.php');
    }

    function it_should_set_and_get_its_class()
    {
      $this->setClass('className');
      $this->getClass()->shouldBe('className');
    }

    function it_should_set_and_get_its_id()
    {
      $this->setId('id');
      $this->getId()->shouldBe('id');
    }

    function it_should_render_itself()
    {
      
    }

    function it_should_return_itself_unless_method_is_getter()
    {
      $self = $this->setTemplate('beans');
      $self->shouldHaveType('Sirs\Surveys\RenderableBlock');

      $self = $this->setClass('className');
      $self->shouldHaveType('Sirs\Surveys\RenderableBlock');

      $self = $this->setId('className');
      $self->shouldHaveType('Sirs\Surveys\RenderableBlock');
    }

}
