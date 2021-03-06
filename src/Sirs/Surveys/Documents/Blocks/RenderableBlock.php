<?php

namespace Sirs\Surveys\Documents\Blocks;

use InvalidArgumentException;
use Sirs\Surveys\HasMetadataTrait;
use Sirs\Surveys\HasParametersTrait;
use Illuminate\Support\Facades\Blade;
use Sirs\Surveys\Documents\XmlDocument;
use Sirs\Surveys\Contracts\RenderableInterface;

/**
 * A renderable XmlDocument block. 
 * The renderable block is the ancestor of all containers (page, question-group, etc.), questions, and other displayable elements of a survey (html, option, etc.)
 */
class RenderableBlock extends XmlDocument implements RenderableInterface
{
    use HasParametersTrait;
    use HasMetadataTrait;

    // public $content;
    protected $class;
    protected $id;
    protected $template;
    protected $defaultTemplatePath;
    protected $documentFactory;
    protected $defaultTemplate = 'block_default';
    protected $renderer;
    protected $parameters;
    protected $metadata;

    public function __construct($xml = null)
    {
        parent::__construct($xml);
    }

    public function parse(\SimpleXMLElement $simpleXmlElement)
    {
        $this->class = $this->getAttribute($simpleXmlElement, 'class');
        $this->name = $this->getAttribute($simpleXmlElement, 'name');
        $this->id = $this->getAttribute($simpleXmlElement, 'id');
        if ($simpleXmlElement->template[0]) {
            $this->template = $this->getAttribute($simpleXmlElement->template[0], 'source');
        }
        $this->parseParameters($simpleXmlElement);
        $this->parseMetadata($simpleXmlElement);
    }

    /**
     * Get the template for this block
     * Returns $this->template if set, otherwise returns $this->defaultTemplate
     *
     * @return string
     **/
    public function getTemplate()
    {
        return ($this->template) ? $this->template : $this->defaultTemplate;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    public function getId()
    {
        return ($this->id) ? $this->id : $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    public function getName()
    {
        return $this->name;
    }

    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    public function getClass()
    {
        return $this->class;
    }

    /**
     * Render the block using it's template
     *
     * @return string
     **/
    public function render($context)
    {
        $chromeTemplate = config('surveys.chromeTemplate', 'default_layout');

        $template = $this->getTemplate();
        
        $templatePath = $template;
        if (!\View::exists($templatePath)) {
            if (substr($template, 0, 7) != 'surveys::') {
                $templatePath = 'surveys::'.$template;
            }
        }

        $view = view(
            $templatePath,
            [
                'context'=>$context,
                'renderable' => $this,
                'chromeTemplate'=>$chromeTemplate
            ]
        );
        return $view->render();
    }

    public function renderWithDefault($defaultTemplate)
    {
        $renderTemplate = $this->getTemplate();

        $view = $this->renderer->render($renderTemplate, ['renderable'=>$this]);

        return $view;
    }

    public function renderWith($template, $context)
    {
        $chromeTemplate = config('surveys.chromeTemplate', 'default_layout');
        $view = $this->renderer->render($template, ['chromeTemplate'=>$chromeTemplate,'context'=>$context, 'renderable'=>$this]);

        return $view;
    }

    public function bladeCompile($value, array $args = [])
    {
        $generated = Blade::compileString($value);

        ob_start() and extract($args, EXTR_SKIP);

        // We'll include the view contents for parsing within a catcher
        // so we can avoid any WSOD errors. If an exception occurs we
        // will throw it out to the exception handler.
        try {
            eval('?>'.$generated);
        }

        // If we caught an exception, we'll silently flush the output
        // buffer so that no partially rendered views get thrown out
        // to the client and confuse the user with junk.
        catch (\Exception $e) {
            ob_get_clean();
            throw $e;
        }

        $content = ob_get_clean();

        return $content;
    }

    public function jsonSerialize()
    {
        return [
            'class' => $this->class,
            'id' => $this->id,
            'name' => $this->name,
            'template' => $this->template,
            'metadata' => $this->metadata
        ];
    }
}
