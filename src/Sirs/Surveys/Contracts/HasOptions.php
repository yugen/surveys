<?php

namespace Sirs\Surveys\Contacts;

/**
 * Interface for Structured data with limited number of possible values
 *
 * @package sirs/survays
 **/
interface HasOptions
{
  /**
   * Sets options for possible values
   *
   * @param array $options
   **/
  public function setOptions(array $options);  

  /**
   * Gets options for possible values
   *
   * @return array or options
   **/
  public function getOptions();
} // END interface EnumStructuredData extends StructuredData