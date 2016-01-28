<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\ContainerInterface;
use Sirs\Surveys\RenderableBlock;
use Sirs\Surveys\Factories\BlockFactory;

class ContainerBlock extends RenderableBlock implements ContainerInterface
{
  use HasQuestionsTrait;

  protected $name;
  protected $contents;
  /**
   * construct a new container
   *
   * @return void
   * @param string $name name
   * @param array $contents
   * @param string $class class name used in rendering
   * @param string $id id used in rendering
   * @param string $template template path
   **/
  function __construct($xml = null){
    $this->contents = [];
    parent::__construct($xml);
  }

  function parse()
  {
    $this->setName($this->getAttribute($this->xmlElement, 'name'));
    // foreach children do the right thing
    $this->setContents($this->parseContents());
    return $this;
  }

  function parseContents(){
    $blockFactory = new BlockFactory();
    $children = [];
    foreach($this->xmlElement->children() as $child){
      $children[] = $blockFactory->create($child);
    }   
    return $children;
  }

  function getQuestions(){
    return [];
  }

  /**
   * sets the name if any
   *
   * @return Sirs\Survey\Container $this
   **/
  function setName($name){
    $this->name = $name;
    return $this;
  }

  /**
   * gets name
   *
   * @return string
   **/
  function getName(){
    return $this->name;
  }

  /**
   * Sets the contents of the container
   *
   * @param array $contents - array of contents
   * @return Sirs\Survey\Container $this
   **/
  public function setContents(array $contents){
    $this->contents = $contents;
    return $this;
  }

  /**
   * Gets the contents of this container
   *
   * @return array - Array of contents
   **/
  public function getContents(){
    return $this->contents;
  }

  /**
   * appends $content to $this->contents
   *
   * @param mixed $content RenderableBlock or array of RenderablBlocks 
   * @return void
   **/
  public function appendContent($content){
    $content = ( is_array($content) ) ? $content : [$content];
    foreach( $content as $idx => $item ){
      array_push($this->contents, $item);
    }
    return $this;
  }

  /**
   * prepends content to $this->contents
   *
   * @param mixed $content RenderableBlock or array of RenderablBlocks 
   * @return void
   **/
  function prependContent($content){
    $content = ( is_array($content) ) ? $content : [$content];
    foreach( array_reverse($content) as $idx => $item ){
      array_unshift($this->contents, $item);
    }
    return $this;
  }

}