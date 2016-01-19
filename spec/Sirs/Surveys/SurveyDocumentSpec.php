<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sirs\Surveys\PageDocument;

class SurveyDocumentSpec extends ObjectBehavior
{
    function let()
    {

      $xml = <<<XML
<?xml version="1.0"?>
<survey 
  name="test" 
  version="1.0.0" 
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
  xsi:schemaLocation="sirs.unc.edu file://'.__DIR__.'/../../../../schema/survey.xsd"
>
  <page name="page1">
    <html>Beans!</html>
    <question name="question1">
      <question-text>This is the first question</question-text>
    </question>
  </page>
  <page name="page2">
    <html>Monkeys!</html>
  </page>
</survey>
XML;

      $this->beConstructedWith($xml);

    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\SurveyDocument');
        $this->shouldImplement('Sirs\Surveys\Contracts\SurveyDocumentInterface');
    }

    function it_validates_a_survey_definition()
    {
      $this->validate()->shouldBe(true);
    }

    function it_sets_and_gets_its_name()
    {
      $name = 'bob';
      $this->setName($name);
      $this->getName()->shouldBe($name);
    }

    function it_sets_and_gets_its_version()
    {
      $version = '1.2.0';
      $this->setVersion($version);
      $this->getVersion()->shouldBe($version);
    }

    function it_gets_and_sets_its_pages(PageDocument $page1, PageDocument $page2)
    {
      $pages = [$page1, $page2];
      $this->setPages($pages);
      $this->getPages()->shouldBe($pages);
    }

    function it_can_append_a_page(PageDocument $page1, PageDocument $page2)
    {
      $this->setPages([$page1]);
      $this->appendPage($page2);
      $this->getPages()->shouldBe([$page1, $page2]);
    }

    function it_can_prepend_a_page(PageDocument $page1, PageDocument $page2)
    {
      $this->setPages([$page1]);
      $this->prependPage($page2);
      $this->getPages()->shouldBe([$page2,$page1]);
    }

    function it_should_get_an_attribute_from_an_xml_element()
    {
      $page = new \SimpleXMLElement("<?xml version=\"1.0\" ?><page name=\"page1\"></page>");
      $this->getAttribute($page, 'name')->shouldBe('page1');

      $this->getAttribute($page, 'beans')->shouldBe(null);
    }

}
