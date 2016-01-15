<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\ContainerInterface;
use Sirs\Surveys\RenderableBlock;

class Container extends RenderableBlock implements ContainerInterface
{
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
  function __construct($name=null, $contents=null, $class = null, $id = null, $template=null){
    $this->setName($name);
    $this->setContents(($contents) ? $contents : []);
    parent::__construct($class, $id, $template);
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