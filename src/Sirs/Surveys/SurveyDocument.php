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
   * @return $this
   * @param string $name
   **/
  function setName($name)
  {
    $this->name = $name;
    return $this;
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
   * @return $this;
   * @param string $version
   **/
  function setVersion($version)
  {
    $this->version = $version;
    return $this;
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
   * @param array $pages of PageDocuments
   * @return $this
   * @param array $pages
   **/
  function setPages($pages)
  {
    $this->pages = $pages;
    return $this;
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
   * @param PageDocument $page
   * @return $this
   * @param Sirs\Surveys\Contracts\PageDocument $page
   **/
  function appendPage(PageDocument $page){
    array_push($this->pages, $page);
    return $this;
  }

  /**
   * Adds a page document to the surveys pages list
   *
   * @param PageDocument $page
   * @return $this
   * @author 
   **/
  public function prependPage(PageDocument $page)
  {
    array_unshift($this->pages, $page);
    return $this;
  }

}
