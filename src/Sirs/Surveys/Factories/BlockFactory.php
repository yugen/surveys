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
      case 'numeric-scale':
      case 'duration':
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
    'date',
    'duration',
    'html',
    'likert',
    'multiple-choice',
    'number',
    'numeric-scale',
    'question-group',
    'question',
    'time',
    'upload',
    ];
    return $whitelist;
  }
}
