<?php

namespace Sirs\Surveys\Factories;

use Sirs\Surveys\Factories\QuestionFactory;
use Sirs\Surveys\Documents\Blocks\HtmlBlock;
use Sirs\Surveys\Exceptions\UnsupportedTagException;
use Sirs\Surveys\Documents\Blocks\Containers\LikertBlock;
use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;

class BlockFactory
{
    protected $blockTypes;

    public function create(\SimpleXMLElement $element)
    {
        $class = $this->getBlockClass($element);
        return new $class($element);
    }

    public function getWhitelist()
    {
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
          'month',
          'year'
        ];
        return $whitelist;
    }

    public function getBlockClass(\SimpleXMLElement $element)
    {
        switch ($element->getName()) {
          case 'container':
          case 'question-group':
            return ContainerBlock::class;
            break;
          case 'likert':
            return LikertBlock::class;
            break;
          case 'date':
          case 'time':
          case 'upload':
          case 'multiple-choice':
          case 'number':
          case 'numeric-scale':
          case 'duration':
          case 'question':
          case 'year':
          case 'month':
            $questionFactory = new QuestionFactory();
            return $questionFactory->getQuestionClass($element);
            break;
          case 'html':
            return HtmlBlock::class;
            break;
          default:
            throw new UnsupportedTagException('Unsupported Tag '.$element->getName());
            break;
        }
    }
}
