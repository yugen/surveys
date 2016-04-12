<?php
namespace Sirs\Surveys;

/**
 * Simple class to represent a variable.
 *
 * @package sirs/surveys
 * @author TJ Ward
 **/
class Variable
{
  public $name;
  public $dataFormat;

  public function __construct($name, $dataFormat)
  {
    $this->name = $name;
    $this->dataFormat = $dataFormat;
  }
} // END class Variable