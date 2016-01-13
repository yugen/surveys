<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Survey;

class SurveyParser
{
    protected $root;
    protected $xml;

    public function __construct($root, $xml)
    {
      $this->root = $root ?: __DIR__.'../../';
      $this->xml = new \SimpleXMLElement($xml);
    }

    public function fetchSurvey($file)
    {
        return file_get_contents($this->getSurveyPath($file));
    }

    protected function getSurveyPath($file){
        return $this->root.'/'.$file;
    }

    public function validate()
    {
        $doc = new \DOMDocument();
        $doc->loadXML($this->xml->asXml());
        return $doc->schemaValidate('schema/survey.xsd');
    }

    public function parse()
    {
        $survey = new Survey();
        return $survey;
        // TODO: write logic here
    }

    public function getPages()
    {
        $pages = [];
        foreach($this->xml->page as $page){
            $pages[] = $page;
        }
        return $pages;
    }
}
