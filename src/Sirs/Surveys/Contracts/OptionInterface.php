<?php
namespace Sirs\Surveys\Contracts;

/**
 * Interface defining Options
 *
 * @package sirs/surveys
 * @author
 **/
interface OptionInterface
{
    /**
     * sets label for option
     *
     * @return void
     **/
    public function setLabel(string $label);

    /**
     * gets label for this option
     *
     * @return string
     **/
    public function getLabel();

    /**
     * sets the value for this option
     *
     * @param mixed $value
     **/
    public function setValue($value);

    /**
     * gets the value for this option
     *
     * @return mixed $value
     **/
    public function getValue();


    /**
     * gets the selected flag for this option
     *
     * @return void
     * @author
     **/
    public function getSelected(bool $selected = null);

    /**
     * Sets the selected flag for this option
     *
     * @return void
     * @author
     **/
    public function setSelected(bool $selected = null);

    /**
     * Returns true if the option is selected
     *
     * @return bool
     **/
    public function isSelected();
} // END interface Option
