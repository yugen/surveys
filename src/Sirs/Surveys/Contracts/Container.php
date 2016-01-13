<?php

namespace Sirs\Surveys\Contacts;

use Sirs\Surveys\Contacts\RenderableBlock;

/**
 * Interface for renderable blocks that can contain other renderable blocks
 *
 * @package sirs/surveys
 * @author 
 **/
interface Container extends RenderableBlock
{
  /**
   * Sets the contents of the container
   *
   * @param array $contents - array of contents
   * @return void
   **/
  public function setContents(array $contents);

  /**
   * Gets the contents of this container
   *
   * @return array - Array of contents
   **/
  public function getContents();
} // END interface Container extends RenderableBlock