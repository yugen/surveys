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
  public function setName($varName);

  /**
   * returns the variable name for this item
   *
   * @return string
   **/
  public function getName();

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

  /**
   * Gets a laravel validator friendly rule string based on attributes
   *
   * @return string
   * @author 
   **/
  public function getValidationString();

  /**
   * gets the name of variabls for the structured data implementation.
   * Should typically return an array with the question name.
   * For multi-select multiple-choice questions it will return the name for each option.
   * 
   * @return array list of variable names.
   */
  public function getVariables();

  public function setRefusable($value);

  public function getRefusable();
} // END interface StructuredData