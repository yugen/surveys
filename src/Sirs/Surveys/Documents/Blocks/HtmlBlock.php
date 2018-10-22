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

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        parent::parse($simpleXmlElement);
        $this->setHtml($simpleXmlElement->content->__toString());
    }

    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getCompiledHtml($context)
    {
        return $this->bladeCompile($this->html, $context);
    }
}
