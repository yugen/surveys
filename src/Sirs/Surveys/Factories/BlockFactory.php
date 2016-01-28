<?php

namespace Sirs\Surveys\Factories;

use Sirs\Surveys\ContainerBlock;
use Sirs\Surveys\Factories\QuestionFactory;
use Sirs\Surveys\HtmlBlock;
use Sirs\Surveys\LikertBlock;

class BlockFactory
{
  protected $blockTypes;

  function create(\SimpleXMLElement $element){
    switch ($element->getName()) {
      case 'container':
      case 'question-group':
        $block = new ContainerBlock($element);
        break;
      case 'likert':
        $block = new LikertBlock($element);
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
