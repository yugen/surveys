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
    public function getType()
    {
        switch ($this->dataFormat) {
            case 'int':
            case 'float':
            case 'double':
            case 'smallint':
            case 'mediumint':
            case 'bigint':
            case 'decimal':
                return 'numeric';
            case 'tinyint':
                return 'boolean';
            case 'char':
            case 'varchar':
            case 'text':
            case 'tinytext':
            case 'mediumtext':
            case 'longtext':
                return 'text';
            default:
                return $this->dataFormat;
        }
    }

    public function __get($attr)
    {
        if (method_exists($this, 'get'.ucfirst($attr))) {
            $method = 'get'.ucfirst($attr);
            return $this->$method();
        }
        return $this->{$attr};
    }
} // END class Variable
