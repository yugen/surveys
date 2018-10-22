<?php

namespace Sirs\Surveys\Contracts;

use Sirs\Surveys\Documents\PageDocument;

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
    public function setName($name);

    /**
     * gets this survey's name
     *
     * @return string
     **/
    public function getName();

    /**
     * sets the version
     *
     * @return void
     * @param string $version
     **/
    public function setVersion($version);

    /**
     * gets this survey's version
     *
     * @return string
     **/
    public function getVersion();

    /**
     * gets pages in this survey
     *
     * @return Sirs\Surveys\Contracts\PageDocument
     * @author
     **/
    public function getPages();

    /**
     * set pages for the survey
     *
     * @return void
     * @param array $pages
     **/
    public function setPages($pages);

    /**
     * adds a page to the end of the survey
     *
     * @return void
     * @param Sirs\Surveys\Contracts\PageDocument $page
     **/
    public function appendPage(PageDocument $page);

    /**
     * prepends a page to the survey
     *
     * @return void
     * @param Sirs\Surveys\Contracts\PageDocument $page
     **/
    public function prependPage(PageDocument $page);

    /**
     * gets all questions in the survey
     *
     * @return array of questionBlocks
     **/
    public function getQuestions();
} // END interface SurveyDocument
