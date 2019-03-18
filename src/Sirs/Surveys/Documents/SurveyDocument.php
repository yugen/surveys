<?php

namespace Sirs\Surveys\Documents;

use Sirs\Surveys\Contracts\SurveyDocumentInterface;
use Sirs\Surveys\HasMetadataTrait;
use Sirs\Surveys\HasParametersTrait;
use Sirs\Surveys\HasQuestionsTrait;
use Sirs\Surveys\XmlValidator;

class SurveyDocument extends XmlDocument implements SurveyDocumentInterface
{
    use HasQuestionsTrait;
    use HasParametersTrait;
    use HasmetadataTrait;

    protected $name;
    protected $title;
    protected $version;
    protected $pages;
    protected $xmlElement;
    protected $template;
    protected $responseLimit;
    protected $rulesClass;
    protected $parameters;
    protected $metadata;
    protected $contents;
    protected $id;

    public function __construct($xml = null)
    {
        $validator = new XmlValidator(__DIR__.'/../survey.xsd');
        $validator->validate($xml);
        $this->pages = [];
        parent::__construct($xml);
    }

    public static function initFromFile($filePath)
    {
        $xmlString = file_get_contents($filePath);
        $class = get_called_class();

        return new $class($xmlString);
    }

    public function getPageByName($name)
    {
        foreach ($this->getPages() as $idx => $page) {
            if ($page->name == $name) {
                return $page;
            }
        }
        throw new \OutOfBoundsException('The page '.$name.' was not found');
    }

    public function getPageIndexByName($name)
    {
        foreach ($this->getPages() as $idx => $page) {
            if ($page->name == $name) {
                return $idx;
            }
        }
        throw new \OutOfBoundsException('The page '.$name.' was not found');
    }

    public function getPageNumberByName($name)
    {
        if ($name == null) {
            foreach ($this->getPages() as $idx => $page) {
                if ($page->name == $name) {
                    return $idx+1;
                }
            }
        }
        throw new \OutOfBoundsException('The page '.$name.' was not found');
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        $this->setTitle($this->getAttribute($simpleXmlElement, 'title'));
        $this->setName($this->getAttribute($simpleXmlElement, 'name'));
        $this->setVersion($this->getAttribute($simpleXmlElement, 'version'));
        $this->setRulesClass($this->getAttribute($simpleXmlElement, 'rules-class'));
        $this->setSurveyId($this->getAttribute($simpleXmlElement, 'survey-id'));
        $this->parseParameters($simpleXmlElement);
        $this->parseMetadata($simpleXmlElement);
        $pageNum = 0;
        foreach ($simpleXmlElement->page as $idx => $pageElement) {
            $pageNum++;
            $page = PageDocument::createWithParameters($pageElement, $this->getParameters());
            $page->setPageNumber($pageNum);
            $this->appendPage($page);
        }
    }

    public function setSurveyId($id)
    {
        $this->id = ($id) ? $id : null;

        return $this;
    }

    public function getSurveyId()
    {
        return $this->id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRulesClass($class)
    {
        $this->rulesClass = $class;

        return $this;
    }

    public function getRulesClass()
    {
        return ($this->rulesClass) ? $this->rulesClass : config('surveys.rulesNamespace', 'App\\Surveys\\').$this->getRulesClassName();
    }

    public function setTemplate($template = null)
    {
        $this->template = $template;

        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * set the title for the page
     *
     * @param string $title
     * @return $this
     **/
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * get the title of the page
     *
     * @return string
     * @author
     **/
    public function getTitle()
    {
        return ($this->title) ? $this->title : ucwords(str_replace('_', ' ', $this->name));
    }

    public function render($context)
    {
    }

    /**
     * sets the survey's name
     *
     * @return $this
     * @param string $name
     **/
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * gets survey's name
     *
     * @return string
     **/
    public function getName()
    {
        return $this->name;
    }

    /**
     * sets the survey's name
     *
     * @return $this;
     * @param string $version
     **/
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * gets survey's version
     *
     * @return string
     **/
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * sets the survey's pages
     *
     * @param array $pages of PageDocuments
     * @return $this
     * @param array $pages
     **/
    public function setPages($pages)
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * gets survey's pages
     *
     * @return array
     **/
    public function getPages()
    {
        return $this->pages;
    }

    public function setContents($contents)
    {
        $this->setPages($contents);
    }

    public function getContents()
    {
        return $this->getPages();
    }

    /**
     * adds a page to the survey
     *
     * @param PageDocument $page
     * @return $this
     * @param Sirs\Surveys\Contracts\PageDocument $page
     **/
    public function appendPage(PageDocument $page)
    {
        array_push($this->pages, $page);

        return $this;
    }

    /**
     * Adds a page document to the surveys pages list
     *
     * @param PageDocument $page
     * @return $this
     * @author
     **/
    public function prependPage(PageDocument $page)
    {
        array_unshift($this->pages, $page);

        return $this;
    }

    public function setResponseLimit($responseLimit)
    {
        $this->responseLimit = $responseLimit;

        return $this;
    }

    public function getResponseLimit()
    {
        return ($this->responseLimit) ? $this->responseLimit : 1;
    }

    public function getPage($pageKey)
    {
        if ($pageKey) {
            if (ctype_digit($pageKey)) {
                if ((int)$pageKey >= count($this->pages)) {
                    return end($this->pages);
                }
                $idx = ((int)$pageKey)-1;

                return $this->pages[$idx];
            }

            return $this->getPageByName($pageKey);
        }

        return $this->pages[0];
    }

    public function getRulesClassName()
    {
        return ucfirst(str_replace('-', '', str_replace('.', '', $this->name.$this->version.'Rules')));
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'title' => $this->title,
            'version' => $this->version,
            'pages' => $this->pages
        ];
    }
}
