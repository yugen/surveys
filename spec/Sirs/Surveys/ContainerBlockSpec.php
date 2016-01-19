<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sirs\Surveys\ContainerBlock;
use Sirs\Surveys\DocumentFactory;
use Sirs\Surveys\QuestionBlock;

class ContainerBlockSpec extends ObjectBehavior
{
    function let()
    {
      $this->beConstructedWith('<container><question name="beans"></question><question name="foo" data-format="int"></question><container><question name="beer" data-format="int"></question><question name="coffee"></question></container></container>');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\ContainerBlock');
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
      $this->setContents([]);
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
      $self->shouldHaveType('Sirs\Surveys\ContainerBlock');

      $self = $this->setName(['beans', 'monkeys']);
      $self->shouldHaveType('Sirs\Surveys\ContainerBlock');

      $self = $this->prependContent(['beer', 'coffee']);
      $self->shouldHaveType('Sirs\Surveys\ContainerBlock');

      $self = $this->appendContent(['beans', 'monkeys']);
      $self->shouldHaveType('Sirs\Surveys\ContainerBlock');
    }

    function it_should_parse_its_contents(QuestionBlock $q1, QuestionBlock $q2, ContainerBlock $container)
    {
      $this->parseContents()->shouldHaveCount(3);
    }

}
