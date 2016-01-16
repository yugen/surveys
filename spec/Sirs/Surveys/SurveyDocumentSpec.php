<?php

namespace spec\Sirs\Surveys;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sirs\Surveys\PageDocument;

class SurveyDocumentSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\SurveyDocument');
        $this->shouldImplement('Sirs\Surveys\Contracts\SurveyDocumentInterface');
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

}
