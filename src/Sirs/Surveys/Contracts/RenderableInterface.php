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
