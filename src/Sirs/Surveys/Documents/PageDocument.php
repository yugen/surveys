<?php

namespace Sirs\Surveys\Documents;

use Sirs\Surveys\Contracts\PageDocumentInterface;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\HasQuestionsTrait;

// class PageDocument implements PageDocumentInterface
class PageDocument extends ContainerBlock implements PageDocumentInterface
{
  use HasQuestionsTrait;

  protected $source;
  protected $title;

  function __construct($xml = null){
    parent::__construct($xml);
  }

  public function parse()
  {
    $this->setTitle($this->getAttribute($this->xmlElement, 'title'));
    $this->setSource($this->getAttribute($this->xmlElement, 'source'));
    parent::parse();
  }

  /**
   * set the source path for the page
   *
   * @param string $source
   * @return $this
   **/
  public function setSource($source)
  {
    $this->source = $source;
    return $this;
  }

  /**
   * get the source path of the page
   *
   * @return string
   * @author 
   **/
  public function getSource()
  {
    return $this->source;
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
    return $this->title;
  }
}