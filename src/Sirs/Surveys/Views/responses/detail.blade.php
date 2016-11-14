@extends('app')

@push('styles')@endpush

@push('scripts')
    <script>
        $('body').scrollspy({ target: '#page-nav' }).css({'position':'relative'})
        $('#page-nav-dropdown').on('hide.bs.dropdown', function(){
          console.log($(this).find('a.active'));
        })
    </script>
@endpush

@section('content')
<h1>
    {{ucwords($response->survey->name)}} response for {{$response->respondent->full_name}}
    <div class="pull-right">
        <nav id="page-nav">
            <div class="dropdown" id="page-nav-dropdown">
                <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default">
                    Pages
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                @foreach($response->survey->surveyDocument->pages as $page)
                    <li><a href="#{{snake_case($page->name)}}"><strong>{{$page->title}}</strong></a></li>
                @endforeach    
                </ul>
            </div>
        </nav>
    </div>
</h1>

<div class="row">
    <div class="col-sm-1">
        <strong>Started:</strong>
        <div>{{$response->started_at}}</div>
    </div>
    <div class="col-sm-1">
        <strong>Updated:</strong>
        <br>
        {{$response->updated_at}}
    </div>
    @if($response->finalized_at)
        <div class="col-sm-1">
            <strong>Finalized:</strong>
            <br>
            {{$response->started_at}}
        </div>
    @endif
    {{-- <div class="col-sm-1">
        <strong>Duration:</strong>
        <br>
        {{$response->duration}}
    </div> --}}
    <div class="col-sm-2">
        <strong>Status:</strong>
        <br>
        @if($response->finalized_at)
            Finalized
            {{-- <button class="btn btn-xs btn-default">Re-open</button> --}}
        @else
            Open
        @endif
    </div>
</div>

<hr>
<div style="height: 500px; overflow-y: scroll;">
@foreach($response->survey->surveyDocument->pages as $page)
    <div class="page-container" id="{{snake_case($page->name)}}">
        <h3>{{$page->title}}</h3>
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
                        <ul class="list-unstyled">
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
                                <li>{{$option->label}}</li>
                            @endforeach
                        @endif
                        </ul>
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
</div>
@endsection