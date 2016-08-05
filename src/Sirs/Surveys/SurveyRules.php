<?php 

namespace Sirs\Surveys;

/**
 * Base Survey Rules Class
 *
 * @package sirs/surveys
 * @author 
 **/
class SurveyRules
{
    protected $response;
    public $pretext;

    /**
     * constructs the survey
     *
     * @return void
     * @author 
     **/
    public function __construct($response)
    {
        $this->response = $response;
        $this->pretext = [];
    }

    public function setPretext($data){
        foreach ($data as $key => $value) {
            if(in_array($key, ['_token', 'page'])) continue;
            if(in_array($key, array_keys($this->response->getDataAttributes()))) continue;
            if(in_array(preg_replace('/(_refused)|(_field)/', '', $key), array_keys($this->response->getDataAttributes()))) continue;

            $this->pretext[$key] = $value;
        }
    }

    /**
     * Overrides get to provide attribute style access
     *
     * @return void
     * @author 
     **/
    public function __get($attr)
    {
        switch ($attr) {
            case 'survey':
                return $this->response->survey;
                break;
            case 'respondent':
                return $this->response->respondent;
                break;
            default:
                return $this->{$attr};
                break;
        }
    }
} // END class SurveyRules