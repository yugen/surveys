<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BlockFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Factories\BlockFactory');
    }

    function it_should_create_a_ContainerBlock_object_when_it_gets_a_container_definition()
    {
      $this->create(new \SimpleXMLElement('<container name="beans"></container>'))->shouldHaveType('Sirs\Surveys\ContainerBlock');
    }

    function it_should_create_a_QuestionDocument_object_when_it_gets_a_question_definition()
    {
      $this->create(new \SimpleXMLElement('<question name="beans"></question>'))->shouldHaveType('Sirs\Surveys\QuestionBlock');
    }

    function it_should_create_a_HtmlBlock_when_it_gets_an_html_tag()
    {
      $this->create(new \SimpleXMLElement('<html></html>'))->shouldHaveType('Sirs\Surveys\HtmlBlock');
    }

    function it_should_thow_an_InvalidArgumentException_if_xml_unrecognized()
    {
      $this->shouldThrow('\InvalidArgumentException')->duringCreate(new \SimpleXMLElement('<beans></beans>'));
    }


}
