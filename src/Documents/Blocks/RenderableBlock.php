<?php

namespace Sirs\Surveys\Documents\Blocks;

use Illuminate\Support\Facades\Blade;
use Sirs\Surveys\Contracts\RenderableInterface;
use Sirs\Surveys\Documents\XmlDocument;
use Sirs\Surveys\HasParametersTrait;
use Windwalker\Renderer\BladeRenderer;

class RenderableBlock extends XmlDocument implements RenderableInterface
{
  use HasParametersTrait;

  protected $class;
  protected $id;
  protected $template;
  protected $defaultTemplatePath;
  protected $documentFactory;
  protected $defaultTemplate = 'block_default';
  protected $renderer;
  protected $parameters;
  public $content;

  public function __construct($xml = null)
  {
    parent::__construct($xml);

  }

  public function parse(\SimpleXMLElement $simpleXmlElement)
  {
    $this->setClass($this->getAttribute($simpleXmlElement, 'class'));
    $this->setName($this->getAttribute($simpleXmlElement, 'name'));
    $this->setId($this->getAttribute($simpleXmlElement, 'id'));
    if( $simpleXmlElement->template[0] ){
      $this->setTemplate($this->getAttribute($simpleXmlElement->template[0], 'source'));
    }
    $this->parseParameters($simpleXmlElement);
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
    // 
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
    return ($this->id) ? $this->id : $this->name;
  }

  public function setName($name){
    $this->name = $name;
    return $this;
  }
  public function getName(){
    return $this->name;
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
   * @return string
   **/
  public function render($context){
    $customTemplatePath = config('surveys.customTemplatePath');
    $paths = new \SplPriorityQueue;
    $paths->insert(__DIR__.'/../../Views', 100);
    if( $customTemplatePath ){
      $paths->insert($customTemplatePath, 200);
    }
    $this->renderer = new BladeRenderer( $paths, [ 
        'cache_path' => config('surveys.rendererConfig.cache_path')
    ]);


    $chromeTemplate = (config('surveys.chromeTemplate')) ? config('surveys.chromeTemplate') : 'chrome';
    $view = $this->renderer->render(
      $this->getTemplate(), 
      [
        'chromeTemplate'=>$chromeTemplate,
        'context'=>$context, 
        'renderable'=>$this
      ]
    );
    return $view;
  }

  public function renderWithDefault($defaultTemplate){
    $renderTemplate = ($this->getTemplate()) ? $this->getTemplate() : $defaultTemplate;
    $view = $this->renderer->render($renderTemplate, ['renderable'=>$this]);
    return $view;
  }

  public function renderWith($template, $context)
  {
    $chromeTemplate = (config('surveys.chromeTemplate')) ? config('surveys.chromeTemplate') : 'chrome';
    $view = $this->renderer->render($template, ['chromeTemplate'=>$chromeTemplate,'context'=>$context, 'renderable'=>$this]);
    return $view;
  }

  public function bladeCompile($value, array $args = array())
  {
      $generated = Blade::compileString($value);

      ob_start() and extract($args, EXTR_SKIP);

      // We'll include the view contents for parsing within a catcher
      // so we can avoid any WSOD errors. If an exception occurs we
      // will throw it out to the exception handler.
      try
      {
          eval('?>'.$generated);
      }

      // If we caught an exception, we'll silently flush the output
      // buffer so that no partially rendered views get thrown out
      // to the client and confuse the user with junk.
      catch (\Exception $e)
      {
          ob_get_clean(); throw $e;
      }

      $content = ob_get_clean();

      return $content;
  }

}
