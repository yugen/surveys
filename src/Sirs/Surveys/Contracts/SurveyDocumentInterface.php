<?php

namespace Sirs\Surveys\Contracts;

use Sirs\Surveys\PageDocument;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
interface SurveyDocumentInterface extends RenderableInterface
{

  /**
   * sets the name
   *
   * @return void
   * @param string $name
   **/
  function setName($name);

  /**
   * gets this survey's name
   *
   * @return string 
   **/
  function getName();

  /**
   * sets the version
   *
   * @return void
   * @param string $version
   **/
  function setVersion($version);

  /**
   * gets this survey's version
   *
   * @return string 
   **/
  function getVersion();

  /**
   * gets pages in this survey
   *
   * @return Sirs\Surveys\Contracts\PageDocument
   * @author 
   **/
  function getPages();

  /**
   * set pages for the survey
   *
   * @return void
   * @param array $pages
   **/
  function setPages($pages);

  /**
   * adds a page to the end of the survey
   *
   * @return void
   * @param Sirs\Surveys\Contracts\PageDocument $page
   **/
  function appendPage(PageDocument $page);

  /**
   * prepends a page to the survey
   *
   * @return void
   * @param Sirs\Surveys\Contracts\PageDocument $page
   **/
  function prependPage(PageDocument $page);

  /**
   * gets all questions in the survey
   *
   * @return array of questionBlocks
   **/
  public function getQuestions();

} // END interface SurveyDocument