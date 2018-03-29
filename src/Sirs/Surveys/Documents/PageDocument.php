<?php

namespace Sirs\Surveys\Documents;

use Sirs\Surveys\Contracts\PageDocumentInterface;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\Factories\QuestionFactory;
use Sirs\Surveys\HasParametersTrait;
use Sirs\Surveys\HasQuestionsTrait;

// class PageDocument implements PageDocumentInterface
class PageDocument extends ContainerBlock implements PageDocumentInterface
{
  use HasQuestionsTrait;

  protected $source;
  protected $title;
  protected $defaultTemplate = 'containers.page.page';
  protected $pageNumber;

  function __construct($xml = null){
    parent::__construct($xml);
  }

  public function parse(\SimpleXMLElement $simpleXmlElement)
  {
    $this->setTitle($this->getAttribute($simpleXmlElement, 'title'));
    $this->setSource($this->getAttribute($simpleXmlElement, 'source'));
    parent::parse($simpleXmlElement);
  }

  /**
   * set the source path for the page
   *
   * @param string $source
   * @return $this
   **/
  public function setPageNumber($pageNumber)
  {
    $this->pageNumber = $pageNumber;
    return $this;
  }

  /**
   * get the pageNumber path of the page
   *
   * @return string
   * @author 
   **/
  public function getPageNumber()
  {
    return $this->pageNumber;
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
    return ($this->title) ? $this->title : ucwords(str_replace('_', ' ', $this->name));
  }

  /**
   * gets an array of key/value pairs for laravel validation
   *
   * @return Array
   * @author SIRS
   **/
  public function getValidation()
  {
    $validation = array();
    $questions = $this->getQuestions();
    foreach ($questions as $question) {
      $validation = array_merge($validation, $question->getLaravelValidationArray());
    }
    return $validation;
  }
}
