<?php

namespace Sirs\Surveys;

class BlockFactory
{
  protected $blockTypes;

  function create(\SimpleXMLElement $element){
    switch ($element->getName()) {
      case 'container':
        $block = new ContainerBlock($element);
        break;
      case 'date':
      case 'time':
      case 'upload':
      case 'multiple-choice':
      case 'number':
      case 'question':
        $questionFactory = new QuestionFactory();
        $block = $questionFactory->create($element);
        break;
      case 'html':
        $block = new HtmlBlock($element);
        break;
      default:
        throw new \InvalidArgumentException('Unsupported Tag '.$element->getName());
        break;
    }
    return $block;
  }
}
