<?php
namespace Sirs\Surveys\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class DictionaryController extends BaseController
{
    public function index(Request $request)
    {
        $surveys = class_survey()::all();
        return response()->view('surveys::dictionary.list', ['surveys'=>$surveys]);
    }

    public function show($surveySlug)
    {
        if ($surveySlug == 'all') {
            return view('surveys::dictionary.all', ['surveys'=>class_survey()::all()]);
        }

        $survey = class_survey()::findBySlug($surveySlug);
        return view('surveys::dictionary.detail', ['survey'=>$survey]);
    }

    public function getCsv($surveySlug)
    {
        $rows = [[
            'Name',
            'Question',
            'Options',
            'Category'
        ]];
        $questions = [];
        if ($surveySlug == 'all') {
            $rows[0][] = 'Survey';
            foreach (class_survey()::all() as $survey) {
                $questions = $survey->document->questions;
                foreach ($questions as $q) {
                    $rows[] = $this->assembleRow($q, $survey);
                }
            }
        } else {
            $survey = class_survey()::findBySlug($surveySlug);
            $questions = $survey->document->questions;
            foreach ($questions as $q) {
                $rows[] = $this->assembleRow($q);
            }
        }

        $handle = fopen(storage_path('app/survey-dictionary-'.$surveySlug.'.csv'), 'w');

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        return response()->download(storage_path('app/survey-dictionary-'.$surveySlug.'.csv'), 'survey-dictionary-'.$surveySlug.'.csv');
    }

    private function assembleRow($question, $survey = null)
    {
        foreach ($question->variables as $idx => $var) {
            if ($question->hasOptions()) {
                $options = '';
                foreach ($question->options as $option) {
                    $options .= $option->value.': '.$option->label."; ";
                }
            } else {
                $options = 'n/a';
            }

            $row = [
                    'Name' => $var->name,
                    'Question' => $question->questionText,
                    'Category' => $var->type,
                    'Options' => $options,
                ];
            if ($survey) {
                $row['Survey'] = $survey->name;
            }

            return $row;
        }
    }
}
