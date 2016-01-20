<?php

namespace Sirs\Surveys\Contracts;

/**
 * Interface for renderable blocks that can contain other renderable blocks
 *
 * @package sirs/surveys
 * @author 
 **/
interface ContainerInterface extends RenderableBlockInterface
{

  /**
   * sets the name if any
   *
   * @return $this
   **/
  function setName($name);

  /**
   * gets name
   *
   * @return string
   **/
  function getName();

  /**
   * Sets the contents of the container
   *
   * @param array $contents - array of contents
   * @return $this
   **/
  public function setContents(array $contents);

  /**
   * Gets the contents of this container
   *
   * @return array - Array of contents
   **/
  public function getContents();

  /**
   * appends $content to $this->contents
   *
   * @param mixed $content RenderableBlock or array of RenderablBlocks 
   * @return this
   **/
  public function appendContent($content);

  /**
   * prepends content to $this->contents
   *
   * @param mixed $content RenderableBlock or array of RenderablBlocks 
   * @return this
   **/
  function prependContent($content);

  /**
   * gets all questions in the survey
   *
   * @return array of questionBlocks
   **/
  public function getQuestions();

} // END interface Container extends RenderableBlock