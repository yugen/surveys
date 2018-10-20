<?php

namespace Sirs\Surveys\Contracts;

use Closure;

/**
 * Interface for renderable blocks
 *
 * @package sirs/surveys
 **/
interface RenderableInterface
{

  /**
   * Sets the template to use for this block
   *
   * @param string $template
   * @return void
   **/
    public function setTemplate($template = null);

    /**
     * Get the template for this block
     *
     * @return string
     **/
    public function getTemplate();

    /**
     * Render the block using it's template
     *
     * @return string
     **/
    public function render($context);
}
