<?php

namespace spec\Sirs\Surveys\Parsers;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContainerParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Parsers\ContainerParser');
    }

    function it_should_create_a_document_object_from_a_container_element()
    {
      $xml = new \SimpleXMLElement('<page name="page1"><html>Beans!</html><question name="question1"><question-text>This is the first question</question-text></question></page>');
      $this->parse($xml)->shouldImplement('Sirs\Surveys\Contracts\ContainerInterface');
    }
}
