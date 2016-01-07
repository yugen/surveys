<?php

namespace Sirs\Survey;

class SurveyParser
{
  protected $root;

    public function __construct($root)
    {
      $this->root = $root ?: __DIR__.'../../';
    }

    public function fetchSurvey($file)
    {
        return file_get_contents($this->getSurveyPath($file));
    }

    protected function getSurveyPath($file){
        return $this->root.'/'.$file;
    }
}
