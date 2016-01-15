<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\SurveyDocumentInterface;
use Sirs\Surveys\PageDocument;

class SurveyDocument implements SurveyDocumentInterface
{
  protected $name;
  protected $version;
  protected $pages;

  public function __construct(string $name = null, string $version = null, $pages = null){
    $this->pages = ($pages) ? $this->setPages($pages) : [];
    $this->name = $this->setName($name);
    $this->version = $this->setVersion($version);
  }

  /**
   * sets the survey's name
   *
   * @return void
   * @param string $name
   **/
  function setName($name)
  {
    $this->name = $name;
  }

  /**
   * gets survey's name
   *
   * @return string
   **/
  public function getName()
  {
    return $this->name;
  }

  /**
   * sets the survey's name
   *
   * @return void
   * @param string $version
   **/
  function setVersion($version)
  {
    $this->version = $version;
  }

  /**
   * gets survey's version
   *
   * @return string
   **/
  public function getVersion()
  {
    return $this->version;
  }

  /**
   * sets the survey's pages
   *
   * @return void
   * @param array $pages
   **/
  function setPages($pages)
  {
    $this->pages = $pages;
  }

  /**
   * gets survey's pages
   *
   * @return array
   **/
  public function getPages()
  {
    return $this->pages;
  }

  /**
   * adds a page to the survey
   *
   * @return void
   * @param Sirs\Surveys\Contracts\PageDocument $page
   **/
  function addPage(PageDocument $page){
    $this->pages[] = $page;
  }

}
