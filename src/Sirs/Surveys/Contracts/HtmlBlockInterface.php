<?php

namespace Sirs\Surveys\Contracts;

use Illuminate\View\Compilers\BladeCompiler;

/**
 * describes a renderable block
 *
 * @package Sirs/Surveys
 * @author
 **/
interface HtmlBlockInterface extends RenderableInterface
{
    /**
     * Html that will be rendered
     *
     * @return $this;
     * @param string $html
     **/
    public function setHtml($html);

    /**
     * returns the HTML
     *
     * @return string
     **/
    public function getHtml();
} // END interface HtmlBlockInterface extends RenderableInterface
