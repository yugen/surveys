<?php

namespace Sirs\Surveys\Contracts;

/**
 * describes a renderable block
 *
 * @package Sirs/Surveys
 * @author 
 **/
interface HtmlBlockInterface extends RenderableBlockInterface
{
  /**
   * Html that will be rendered
   *
   * @return $this;
   * @param string $html
   **/
  function setHtml($html);

  /**
   * returns the HTML
   *
   * @return string
   **/
  function getHtml();
} // END interface HtmlBlockInterface extends RenderableBlockInterface