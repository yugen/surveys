@foreach($response->survey->surveyDocument->pages as $page)
<div class="page-container" id="{{snake_case($page->name)}}">
    <h3>
        {{$page->title}}
        @if(count($page->getQuestions()) > 0)
            <a href="{{route('survey_get', [get_class($response->respondent), $response->respondent->id, $response->survey->slug, $response->id])}}?page={{$page->name}}" class="btn btn-xs btn-default pull-right">Edit Page</a>
        @endif
    </h3>
    @if(count($page->getQuestions()) > 0)
    <table class="table table-striped table-bordered">
        <tr>
            <th style="width: 20%">Variable Name</th>
            <th style="width: 50%">Question Text</th>
            <th style="width: 20%">Value</th>
            <th style="width: 10%">Raw Value</th>
        </tr>
        @foreach($page->getQuestions() as $question)
        <tr>
            <td>{{$question->name}}</td>
            <td>{!! $question->questionText !!}</td>
            <td>
                @if($question->hasOptions())
                    @if($question->numSelectable > 1)
                        @foreach($question->getVariables() as $var)
                            @if($response->{$var->name} == 1)
                                @foreach($question->options as $option)
                                    {{-- option: {{$option->name}}<br /> --}}
                                    @if($option->name == $var->name)
                                        <li>{{ $option->label }}</li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        @foreach($question->getOptionsForResponseValue($response->{$question->name}) as $option)
                            {{$option->label}}
                        @endforeach
                    @endif
                @else
                    @if($response->{$question->name})
                        {{($response->{$question->name} == -77) ? config('surveys.refuseLabel', 'Refused') : $response->{$question->name} }}
                    @endif 
                @endif
            </td>
            <td>
                @if(!$question->hasOptions())
                    {{$response->{$question->name} or 'null'}}
                @elseif( $question->hasOptions())
                    {{$response->{$question->name} or 'null'}}
                @else
                    --
                @endif
            </td>
        </tr>
        @endforeach
    </table>     
    @else
        <div class="well">This page does not have any questions.</div>
    @endif
@endforeach
