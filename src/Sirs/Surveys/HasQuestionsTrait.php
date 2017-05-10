<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Documents\Blocks\Containers\ContainerBlock;
use Sirs\Surveys\Documents\Blocks\Questions\QuestionBlock;
use Sirs\Surveys\Exceptions\QuestionNotFoundException;
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
                $questions[$idx] = $block;
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
        $questions = $this->getQuestions();
        return isset($questions[$name]);
    }

    /**
     * Get a question by it's name attribute
     *
     * @param  string $name
     * @return Sirs\Surveys\Documents\Blocks\Questions\Question
     * @throws Exception Exception thrown when 
     **/
    public function getQuestionByName($name)
    {
        $questions = $this->getQuestions();
        if ($this->hasQuestion($name)) {
            return $questions[$name];
        } else {
            throw new QuestionNotFoundException('Question '.$name.' not found in the container '.$this->name);
        }
    }
}
