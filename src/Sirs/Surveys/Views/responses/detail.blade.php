@extends(config('surveys.chromeTemplate'))

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

<div>
    <div id="lifecycle" class="flexbox-container">
        <div>
            <strong>Started:</strong>
            <br>
            {{$response->started_at->format('m/d/Y h:i a')}}
        </div>
        <div>
            <strong>Updated:</strong>
            <br>
            {{$response->updated_at->format('m/d/Y h:i a')}}
        </div>
        <div>
            <strong>Finalized:</strong>
            <br>
            @if($response->finalized_at)
                {{$response->finalized_at->format('m/d/Y h:i a')}}
            @endif
        </div>
        {{-- <div>
            <strong>Duration:</strong>
            <br>
            {{$response->duration}}
        </div> --}}
        <div>
            <strong>Status:</strong>
            <br>
            @if($response->finalized_at)
                Finalized
            @elseif($response->started_at)
                In progress
            @else
                Untouched
            @endif
        </div>
        @can('editFinalized', $response)
        <div>
            <a href="{{$surveyRoute}}" class="btn btn-sm btn-default">Edit Response Data</a>
        </div>
        @endcan
        <div>
            <a href="#showHistory" data-toggle="modal" data-target="#revision-history-modal">Show Revision History</a>
        </div>
    </div>
</div>

<div style="height: 500px; overflow-y: scroll; padding-top: 1em; border-top: 1px solid #aaa; margin-top: 1em;">
    @include('surveys::responses.var_list', ['response'=>$response])
</div>

@include('surveys::responses.revisions');
@endsection