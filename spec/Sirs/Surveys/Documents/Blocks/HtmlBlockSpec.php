<?php

namespace spec\Sirs\Surveys\Documents\Blocks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HtmlBlockSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Documents\Blocks\HtmlBlock');
    }

    function it_should_set_and_get_its_contents()
    {
      $html = '<p>this is a paragraph</p>';
      $this->setHtml($html);
      $this->getHtml()->shouldBe($html);
    }

    function it_should_convert_an_xml_node()
    {
      $html = '<p>paragraph</p>';
      $xml = '<html><![CDATA['.$html.']]></html>';
      $this->beConstructedWith($xml);
      $this->getHtml()->shouldBe($html);
    }

}
