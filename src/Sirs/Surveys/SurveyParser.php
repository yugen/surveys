<?php

namespace Sirs\Surveys;

use Sirs\Surveys\PageDocument;
use Sirs\Surveys\Parsers\ContainerParser;
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
        $pageParser = new ContainerParser();
        foreach( $this->xml->page as $pageElement ){
            $survey->appendPage($pageParser->parse($pageElement));
        }
        $survey->setPages($pageParser);
        return $survey;
        // TODO: write logic here
    }

    public function getAttribute(\SimpleXMLElement $simpleXmlEl, $attribute)
    {
        if( $simpleXmlEl->attributes()->{$attribute} ){
            return $simpleXmlEl->attributes()->{$attribute}->__toString();
        }
    }
}
