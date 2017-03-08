<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\Documents\Blocks\Questions\QuestionBlock;
use Sirs\Surveys\Factories\QuestionFactory;

trait HasQuestionsTrait
{

    /**
     * Get all questions for this block.
     *
     * @return array
     */
    public function getQuestions()
    {
        $questions = [];
        foreach ($this->getContents() as $idx => $block) {
            if ($block instanceof QuestionBlock) {
                $questions[] = $block;
            } elseif ($block instanceof ContainerBlock) {
                $questions = array_merge($questions, $block->getQuestions());
            }
        }
        return $questions;
    }

    /**
     * gets variable names for all questions in this container
     *
     * @return array
     **/
    public function getVariables()
    {
        $varNames = [];
        foreach ($this->getQuestions() as $question) {
            $varNames = array_merge($varNames, $question->getVariables());
        }
        return $varNames;
    }

    /**
     * Check for question by name attribute
     *
     * @param  string  $name
     * @return boolean
     **/
    public function hasQuestion($name)
    {
        foreach ($this->getQuestions() as $question) {
            if ($question->name  == $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get a question by it's name attribute
     *
     * @param  string $name
     * @return Sirs\Surveys\Documents\Blocks\Questions\Question
     **/
    public function getQuestionByName($name)
    {
        foreach ($this->getQuestions() as $question) {
            if ($question->name == $name) {
                return $question;
            }
        }
        return null;
    }
}
