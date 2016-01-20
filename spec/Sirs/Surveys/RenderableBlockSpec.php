<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sirs\Surveys\BlockFactory;

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

    function it_should_have_a_default_template()
    {
      $this->getTemplate()->shouldBe('blocks/default.blade.php');
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

    function it_should_build_itself_based_on_the_xml()
    {
      $this->setXmlElement('<container class="test" id="test"><question name="testQuestion" id="testQueston" class="class"></question></container>');
      $this->getId()->shouldBe('test');
      $this->getClass()->shouldBe('test');
    }

}
