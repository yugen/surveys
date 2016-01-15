<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContainerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Container');
    }

    function its_constructor_should_take_its_properties()
    {
      $this->beConstructedWith('$name', ['contents'], '$class', '$id', '$template');
      $this->getName()->shouldBe('$name');
      $this->getContents()->shouldBe(['contents']);
      $this->getClass()->shouldBe('$class');
      $this->getId()->shouldBe('$id');
      $this->getTemplate()->shouldBe('$template');
    }

    function it_should_get_and_set_its_name()
    {
      $self = $this->setName('beans');
      $this->getName()->shouldBe('beans');
    }

    function it_should_get_and_set_its_contents()
    {
      $self = $this->setContents(['beans', 'monkeys']);
      $this->getContents()->shouldHaveCount(2);
    }

    function it_should_append_stuff_to_its_contents()
    {
      $this->appendContent('item');
      $this->getContents()->shouldBe(['item']);

      $self = $this->appendContent(['beans', 'monkeys']);
      $this->getContents()->shouldBe(['item', 'beans', 'monkeys']);
    }

    function it_should_prepend_stuff_to_its_contents()
    {
      $this->setContents(['beans','monkeys']);
      $this->prependContent('item');
      $this->getContents()->shouldBe(['item', 'beans', 'monkeys']);

      $this->prependContent(['beer', 'coffee']);
      $this->getContents()->shouldBe(['beer', 'coffee', 'item', 'beans', 'monkeys']);
    }

    function it_should_return_itself_unless_method_is_getter()
    {
      $self = $this->setContents(['beans', 'monkeys']);
      $self->shouldHaveType('Sirs\Surveys\Container');

      $self = $this->setName(['beans', 'monkeys']);
      $self->shouldHaveType('Sirs\Surveys\Container');

      $self = $this->prependContent(['beer', 'coffee']);
      $self->shouldHaveType('Sirs\Surveys\Container');

      $self = $this->appendContent(['beans', 'monkeys']);
      $self->shouldHaveType('Sirs\Surveys\Container');
    }
}
