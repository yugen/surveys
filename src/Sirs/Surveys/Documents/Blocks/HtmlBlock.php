<?php

namespace Sirs\Surveys\Documents\Blocks;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Sirs\Surveys\Contracts\HtmlBlockInterface;
use Sirs\Surveys\Documents\Blocks\RenderableBlock;

class HtmlBlock extends RenderableBlock implements HtmlBlockInterface
{
  protected $html;
  protected $defaultTemplate = 'html_default';

  function parse()
  {
    parent::parse();
    $this->setHtml($this->xmlElement->content->__toString());
  }

  function setHtml($html)
  {
    $this->html = $html;
    return $this;
  }

  function getHtml()
  {
    return $this->html;
  }

  function render($context){
    return $this->bladeCompile($this->html, $context);
  }

}
