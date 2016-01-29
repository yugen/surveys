<?php

namespace spec\Sirs\Surveys\Documents\Blocks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sirs\Surveys\Factories\BlockFactory;

class RenderableBlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Documents\Blocks\RenderableBlock');
    }

    function it_should_implement_RenderableInterface()
    {
      $this->shouldImplement('Sirs\Surveys\Contracts\RenderableInterface');
    }

    function it_should_have_a_default_template()
    {
      $this->getTemplate()->shouldBe('block_default');
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
      $this->content = '<p>beans!!</p>';
      $this->render()->shouldBe('<div><p>beans!!</p></div>');
    }

    function it_should_return_itself_unless_method_is_getter()
    {
      $self = $this->setTemplate('beans');
      $self->shouldHaveType('Sirs\Surveys\Documents\Blocks\RenderableBlock');

      $self = $this->setClass('className');
      $self->shouldHaveType('Sirs\Surveys\Documents\Blocks\RenderableBlock');

      $self = $this->setId('className');
      $self->shouldHaveType('Sirs\Surveys\Documents\Blocks\RenderableBlock');
    }

    function it_should_build_itself_based_on_the_xml()
    {
      $this->setXmlElement('<container class="test" id="test"><question name="testQuestion" id="testQueston" class="class"></question></container>');
      $this->getId()->shouldBe('test');
      $this->getClass()->shouldBe('test');
    }

}
