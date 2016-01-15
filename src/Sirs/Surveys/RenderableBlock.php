<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\RenderableBlockInterface;

class RenderableBlock implements RenderableBlockInterface
{
  protected $class;
  protected $id;
  protected $template;

  public function __construct($class = null, $id = null, $template=null)
  {
    $this->setClass($class);
    $this->setId($id);
    $this->setTemplate($template);
  }



  /**
   * Sets the template to use for this block
   *
   * @param string $template
   * @return void
   **/
  public function setTemplate($template = null){
    $this->template = $template;
    return $this;
  }

  /**
   * Get the template for this block
   *
   * @return string
   **/
  public function getTemplate(){
    return $this->template;
  }

  public function setId($id){
    $this->id = $id;
    return $this;
  }
  public function getId(){
    return $this->id;
  }

  public function setClass($class){
    $this->class = $class;
    return $this;
  }

  public function getClass(){
    return $this->class;
  }

  /**
   * Render the block using it's template
   *
   * @param Closure $beforeRender - function to call before rendering the block
   * @param Closure $afterRender - function to call after rendering
   * @return string
   **/
  public function render(Closure $beforeRender = null, Closure $afterRender = null){
    
  }
}
