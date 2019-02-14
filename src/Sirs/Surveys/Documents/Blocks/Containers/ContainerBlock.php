<?php

namespace Sirs\Surveys\Documents\Blocks\Containers;

use Sirs\Surveys\Contracts\ContainerInterface;
use Sirs\Surveys\Documents\Blocks\RenderableBlock;
use Sirs\Surveys\Factories\BlockFactory;
use Sirs\Surveys\HasQuestionsTrait;

class ContainerBlock extends RenderableBlock implements ContainerInterface
{
    use HasQuestionsTrait;

    protected $name;
    protected $contents;
    /**
     * construct a new container
     *
     * @return void
     * @param string $name name
     * @param array $contents
     * @param string $class class name used in rendering
     * @param string $id id used in rendering
     * @param string $template template path
     **/
    public function __construct($xml = null)
    {
        $this->contents = [];
        parent::__construct($xml);
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        $this->setName($this->getAttribute($simpleXmlElement, 'name'));
        parent::parse($simpleXmlElement);
        // foreach children do the right thing
        $this->setContents($this->parseContents($simpleXmlElement));

        return $this;
    }

    public function parseContents(\SimpleXMLElement $simpleXmlElement)
    {
        $blockFactory = new BlockFactory();
        $children = [];
        foreach ($simpleXmlElement->children() as $child) {
            if (in_array($child->getName(), $blockFactory->getWhitelist())) {
                $childClass = $blockFactory->getBlockClass($child);
                $childBlock = $childClass::createWithParameters($child, $this->getParameters());
                if (!$childBlock->name) {
                    $classParts = explode('\\', $childClass);
                    $childBlock->name = uniqid(end($classParts).'-', false);
                }
                $children[$childBlock->name] = $childBlock;
            }
        }

        return $children;
    }

    /**
     * sets the name if any
     *
     * @return Sirs\Survey\Container $this
     **/
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * gets name
     *
     * @return string
     **/
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the contents of the container
     *
     * @param array $contents - array of contents
     * @return Sirs\Survey\Container $this
     **/
    public function setContents(array $contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Gets the contents of this container
     *
     * @return array - Hash of contents
     **/
    public function getContents()
    {
        return $this->contents;
    }

    public function getOrderedContents()
    {
        return array_values($this->contents);
    }

    /**
     * appends $content to $this->contents
     *
     * @param mixed $content RenderableBlock or array of RenderablBlocks
     * @return void
     **/
    public function appendContent($content)
    {
        $content = (is_array($content)) ? $content : [$content];
        foreach ($content as $idx => $item) {
            array_push($this->contents, $item);
        }

        return $this;
    }

    /**
     * prepends content to $this->contents
     *
     * @param mixed $content RenderableBlock or array of RenderablBlocks
     * @return void
     **/
    public function prependContent($content)
    {
        $content = (is_array($content)) ? $content : [$content];
        foreach (array_reverse($content) as $idx => $item) {
            array_unshift($this->contents, $item);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'contents' => $this->contents
        ]);
    }
}
