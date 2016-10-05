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
<h1>{{ucwords($response->survey->name)}} response for {{$response->respondent->full_name}}</h1>
<div class="alert alert-info">
    This survey response has been finalized.
</div>
{{-- <div class="row">
    <div class="col-sm-2">
        <nav id="page-nav" data-spy="affix" data-offset-top="200">
            <div class="dropdown" id="page-nav-dropdown">
                <button id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default">
                    Dropdown trigger
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
    <div class="col-sm-10">
 --}}    @foreach($response->survey->surveyDocument->pages as $page)
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
                            @if(isset($question->numSelectable) && $question->numSelectable > 1)
                                @foreach($question->getVariables() as $var)
                                    @if($response->{$var->name})
                                        <li>{{ $var->name }}</li>
                                    @endif
                                @endforeach
                            @else
                                @foreach($question->getOptionsForResponseValue($response->{$question->name}) as $option)
                                    <li>{{$option->name}}</li>
                                @endforeach
                            @endif
                            </ul>
                        @else
                            {{$response->{$question->name} or 'null'}}
                        @endif
                    </td>
                    <td>
                        @if(!$question->hasOptions())
                            
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
        {{-- </div>  --}}
    @endforeach
    {{-- </div> --}}
@endsection