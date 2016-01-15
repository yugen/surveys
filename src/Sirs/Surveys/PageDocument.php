<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\PageDocumentInterface;

// class PageDocument implements PageDocumentInterface
class PageDocument extends Container implements PageDocumentInterface
{
  protected $source;
  protected $title;

  function __construct($title=null,$source=null,$name=null, $contents=null, $class = null, $id = null, $template=null){
    $this->setTitle($title);
    $this->setSource($source);
    parent::__construct($name, $contents, $class, $id, $template);
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
