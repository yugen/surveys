<?php

namespace spec\Sirs\Survey;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
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

      $this->beConstructedWith(VfsStream::url('root_dir'));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Survey\SurveyParser');
    }

    function it_fetches_a_surey_definition()
    {
      $this->fetchSurvey("foo.txt")->shouldReturn('foobar');
    }

    // function it_gets_the_contents_of_survey_definition()
    // {
        
    // }

    // function it_validates_the_survey_definition()
    // {

    // }

}
