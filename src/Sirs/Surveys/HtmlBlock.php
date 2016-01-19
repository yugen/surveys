<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Contracts\HtmlBlockInterface;

class HtmlBlock extends RenderableBlock implements HtmlBlockInterface
{
  protected $html;

  function parse()
  {
    parent::parse();
    $this->setHtml($this->xmlElement->__toString());
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

}
