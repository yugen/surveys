<?php

namespace Sirs\Surveys;

use Sirs\Surveys\PageDocument;
use Sirs\Surveys\SurveyDocument;

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
        $survey = new SurveyDocument(); // change to SurveyDefinition
        return $survey;
        // TODO: write logic here
    }

    public function getPages()
    {
        $pages = [];
        foreach($this->xml->page as $page){
            $name = $this->getAttribute($page, 'name');
            $title = $this->getAttribute($page, 'title');
            $source = $this->getAttribute($page, 'src');
            $class = $this->getAttribute($page, 'class');
            $id = $this->getAttribute($page, 'id');
            $contents = [];
            $template = '';

            $pages[] = new PageDocument($title, $source, $name, $contents, $class, $id, $template);
        }
        return $pages;
    }

    public function getAttribute(\SimpleXMLElement $simpleXmlEl, $attribute)
    {
        if( $simpleXmlEl->attributes()->{$attribute} ){
            return $simpleXmlEl->attributes()->{$attribute}->__toString();
        }
    }
}
