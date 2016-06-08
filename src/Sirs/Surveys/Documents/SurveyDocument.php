<?php

namespace Sirs\Surveys\Documents;

use Sirs\Surveys\Contracts\RenderableInterface;
use Sirs\Surveys\Contracts\SurveyDocumentInterface;
use Sirs\Surveys\Documents\PageDocument;
use Sirs\Surveys\Documents\XmlDocument;
use Sirs\Surveys\HasQuestionsTrait;

class SurveyDocument extends XmlDocument implements SurveyDocumentInterface
{
  use HasQuestionsTrait;

  protected $name;
  protected $title;
  protected $version;
  protected $pages;
  protected $xmlElement;
  protected $template;
  protected $responseLimit;

  public function __construct($xml = null)
  {
    $this->pages = [];
    parent::__construct($xml);
  }

  static public function initFromFile($filePath){
    $xmlString = file_get_contents($filePath);
    $class = get_called_class();
    return new $class($xmlString);
  }

  public function getPageByName($name)
  {
    foreach( $this->getPages() as $idx => $page ){
      if( $page->name == $name ){
        return $page;
      }
    }
    throw new \OutOfBoundsException('The page '.$name.' was not found');
  }

  public function getPageIndexByName($name)
  {
    foreach( $this->getPages() as $idx => $page ){
      if( $page->name == $name ){
        return $idx;
      }
    }
    throw new \OutOfBoundsException('The page '.$name.' was not found');
  }



  public function parse()
  {
      foreach( $this->xmlElement->page as $pageElement ){
          $this->appendPage(new PageDocument($pageElement));
      }
      $this->setTitle($this->getAttribute($this->xmlElement, 'title'));
      $this->setName($this->getAttribute($this->xmlElement, 'name'));
      $this->setVersion($this->getAttribute($this->xmlElement, 'version'));
  }

  public function setTemplate($template = null)
  {
    $this->template = $template;
    return $this;
  }

  public function getTemplate()
  {
    return $this->template;
  }

  /**
   * set the title for the page
   *
   * @param string $title
   * @return $this
   **/
  public function setTitle($title)
  {
    $this->title = $title;
    return $this;
  }

  /**
   * get the title of the page
   *
   * @return string
   * @author 
   **/
  public function getTitle()
  {
    return ($this->title) ? $this->title : ucwords(str_replace('_', ' ', $this->name));
  }

  public function render($context)
  {
    
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

  public function setResponseLimit($responseLimit)
  {
    $this->responseLimit = $responseLimit;
    return $this;
  }

  public function getResponseLimit()
  {
      return ($this->responseLimit) ? $this->responseLimit : 1;
  }

  public function getPage($pageKey){
    if( $pageKey ){
        if(ctype_digit($pageKey)){
            $idx = ((int)$pageKey)-1;
            return $this->pages[$idx];
        }else{
            return $this->getPageByName($pageKey);
        }
    }else{
        return $this->pages[0];
    }
  }

}
