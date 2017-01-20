<?php

namespace Sirs\Surveys;

/**
 * A simple container for pretext data to make it easier to work with.
 *
 * @package sirs/surveys
 * @author 
 **/
class RulesPretext
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function save()
    {
        request()->session()->put('pretext', $this->getData());        
    }

    public function getData()
    {
        return $this->data;
    }

    public function __set($attr, $val)
    {
        if(property_exists(get_class($this), $attr)){
            $this->{$attr} = $val;
        }else{
            $this->data[$attr] = $val;
        }
    }

    /**
     * allows access to $data elements using object syntax
     *
     * @return mixed
     **/
    public function __get($attr)
    {
        if (property_exists(get_class($this), $attr)) {
            return $this->{$attr};
        }else{
            if (isset($this->data[$attr])) {
                return $this->data[$attr];
            }
            return null;
        }

    }
} // END class RulesPretext