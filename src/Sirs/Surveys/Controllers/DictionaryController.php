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
        $questions = [];
        if ($surveySlug == 'all') {
            foreach (class_survey()::all() as $survey) {                
                $questions = array_merge($questions, (array)$survey->document->questions);
            }
        } else {
            $survey = class_survey()::findBySlug($surveySlug);
            $questions = $survey->document->questions;
        }

        $rows = [
            ['Name', 'Question', 'Options', 'Category', 'Survey']
        ];
        foreach($questions as $q){
            foreach($q->variables as $idx => $var){
                if($q->hasOptions()) {
                    $options = '';
                    foreach($q->options as $option){
                        $options .= $option->value.': '.$option->label."; ";
                    }
                } else {
                    $options = 'n/a';
                }

                $rows[] = [
                    'Name' => $var->name,
                    'Question' => $q->questionText,
                    'Category' => $var->type,
                    'Options' => $options,
                ];
            }   
        }
        $handle = fopen(storage_path('app/survey-dictionary-'.$surveySlug.'.csv'), 'w');

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        return response()->download(storage_path('app/survey-dictionary-'.$surveySlug.'.csv'), 'survey-dictionary-'.$surveySlug.'.csv');

    }
}
