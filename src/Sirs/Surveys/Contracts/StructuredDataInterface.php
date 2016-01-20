<?php

namespace Sirs\Surveys\Contracts;

/**
 * An interface for objects that represent structured data
 *
 * @package sirs/surveys
 * @author 
 **/
interface StructuredDataInterface
{
  /**
   * sets the variable name for this item
   *
   * @param string $varName
   * @return string
   **/
  public function setVariableName($varName);

  /**
   * returns the variable name for this item
   *
   * @return string
   **/
  public function getVariableName();

  /**
   * sets the datatype for this item
   *
   * @param string
   **/
  function setDataFormat($dataFormat);

  /**
   * returns the datatype for this item
   *
   * @return string
   **/
  function getDataFormat();

  /**
   * sets the question text used to collect this data
   *
   * @param string $questionText
   * @author 
   **/
  public function setQuestionText($questionText);

  /**
   * gets the question text used to collect this data
   *
   * @return void
   * @author 
   **/
  public function getQuestionText();

  /**
   * returns a data definition for this item
   *
   * @return void
   * @author 
   **/
  public function getDataDefinition();
} // END interface StructuredData