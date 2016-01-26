<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\RenderableBlockInterface;
use Windwalker\Renderer\BladeRenderer;

class RenderableBlock extends XmlDocument implements RenderableBlockInterface
{
  protected $class;
  protected $id;
  protected $template;
  protected $defaultTemplatePath;
  protected $documentFactory;
  protected $defaultTemplate;
  protected $renderer;
  public $content;

  public function __construct($xml = null)
  {
    parent::__construct($xml);
    $this->defaultTemplatePath = __DIR__.'/Views';
    $this->defaultTemplate = 'block_default';
    $this->renderer = new BladeRenderer([$this->defaultTemplatePath], [ 'cache_path' => __DIR__.'/cache' ]);
  }

  public function parse()
  {
    $this->setClass($this->getAttribute($this->xmlElement, 'class'));
    $this->setId($this->getAttribute($this->xmlElement, 'id'));
    $this->setTemplate($this->xmlElement->template[0]);
  }

  /**
   * Sets the template to use for this block
   *
   * @param string $template
   * @return void
   **/
  public function setTemplate($template = null){
    // print("\n----\n RenderableBlock::setTemplate \n---\n");
    // print('template: '. $template."\n");
    $this->template = $template;
    return $this;
  }

  /**
   * Get the template for this block
   *
   * @return string
   **/
  public function getTemplate(){
    return ($this->template) ? $this->template : $this->defaultTemplate;
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
    if( $beforeRender ){
      $beforeRender($this);
    }    

    $view = $this->renderer->render($this->getTemplate(), ['renderable'=>$this]);

    if( $afterRender ){
      $afterRender($this);
    }
    return $view;
  }
}
