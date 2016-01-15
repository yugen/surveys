<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sirs\Surveys\Survey;
use org\bovigo\vfs\VfsStream;
use org\bovigo\vfs\VfsStreamWrapper;

class SurveyParserSpec extends ObjectBehavior
{

    function let()
    {
      $structure = [
        'foo.txt'=>'foobar',
      ];

      VfsStream::setup('root_dir', null, $structure);

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

      $this->beConstructedWith(VfsStream::url('root_dir'), $xml);

    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\SurveyParser');
    }

    function it_fetches_a_survey_definition()
    {
      $this->fetchSurvey("foo.txt")->shouldReturn('foobar');
    }

    function it_validates_a_survey_definition()
    {
      $this->validate()->shouldBe(true);
    }

    function it_should_return_a_survey_object()
    {
      $this->parse()->shouldHaveType('Sirs\Surveys\SurveyDocument');
    }

    function it_should_get_a_list_of_pages_in_the_survey()
    {
      $pages = $this->getPages()->shouldHaveCount(2);
    }

}
