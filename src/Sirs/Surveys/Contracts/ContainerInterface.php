<?php

namespace Sirs\Surveys\Contracts;

/**
 * Interface for renderable blocks that can contain other renderable blocks
 *
 * @package sirs/surveys
 * @author
 **/
interface ContainerInterface extends RenderableInterface
{

  /**
   * sets the name if any
   *
   * @return $this
   **/
    public function setName($name);

    /**
     * gets name
     *
     * @return string
     **/
    public function getName();

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
    public function prependContent($content);

    /**
     * gets all questions in the survey
     *
     * @return array of questionBlocks
     **/
    public function getQuestions();

    /**
     * gets all variable names for questions in a container
     * @return array
     */
    public function getVariables();
} // END interface Container extends RenderableBlock
