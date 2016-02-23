<?php

namespace Sirs\Surveys\Factories;

use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\Documents\Blocks\Containers\LikertBlock;
use Sirs\Surveys\Documents\Blocks\HtmlBlock;
use Sirs\Surveys\Factories\QuestionFactory;

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

  function getWhitelist(){
    $whitelist = [
    'container',
    'question-group',
    'likert',
    'date',
    'time',
    'upload',
    'multiple-choice',
    'number',
    'question',
    'html'
    ];
    return $whitelist;
  }
}
