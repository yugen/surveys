@extends($chromeTemplate)

@section('content')
<form class="sirs-survey" method="POST" name="{{$context['survey']['name']}}-{{$renderable->name}}" novalidate>
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right" style="margin-top: 4px;">
        <a href="{{route('surveys.{surveySlug}.responses.show', [$context['survey']['object']->slug, $context['response']->id])}}" class="btn btn-sm btn-default">View Data</a>
      </div>
      <h4>
        <a href="{{route('participants.show', [$context['response']->respondent->id])}}">
        {{$context['response']->respondent->full_name or 'Respondent:'.$context['response']->respondent->id}} 
        </a>
        - 
        {{ $context['survey']['title'] or ucwords($context['survey']['name'])}}        
        - {{$renderable->title}}
        <small class="pull-right">v.{{$context['survey']['version']}}</small>
      </h4>
    </div>
    <div class="panel-body">
      @if($renderable->contents)
        @foreach($renderable->contents as $content)
          {!! $content->render($context) !!}
        @endforeach
      @else
        <div class="alert-danger">
          No contents found for this page!!
        </div>
      @endif
    </div>
    <div class="panel-footer">
      @if($context['survey']['currentPageIdx'] > 0)
        <button id="nav-prev" type="submit" name="nav" value="prev" class="btn btn-default">Back</button>
      @endif
      @if($context['survey']['currentPageIdx'] < ($context['survey']['totalPages']-1))
      <button id="nav-next" type="submit" name="nav" value="next" class="btn btn-primary">Next</button>
      @endif
      @if($context['survey']['currentPageIdx'] == ($context['survey']['totalPages']-1))
        <button id="nav-finalize" type="submit" name="nav" value="finalize" class="btn btn-primary">Finish &amp; Finalize</button>
      @endif
      <div id="save-buttons" class="pull-right">
        @if(!isset($context['hideSave']) || !$context['hideSave'])
        <button id="nav-save" type="submit" name="nav" value="save" class="btn btn-default">Save</button>
        @endif
        @if(!isset($context['hideSaveExit']) || !$context['hideSaveExit'])
        <button id="nav-save-exit" type="submit" name="nav" value="save_exit" class="btn btn-default">Save &amp; exit</button>
        @endif
      </div>
    </div>
  </div>
</form>
@endsection
@push('scripts')
<script>
  $(document).ready(function(){
    $('[data-skipTarget]').skipTrigger();
    $('.mutually-exclusive').mutuallyExclusive();
    $('.sm_datepicker').datepicker({
        format: "mm/dd/yyyy"
    });

    $('.timepicker').timepicker({
      minTime: '5:00am',
      maxTime: '7:00pm'
    }).on('keydown', function(evt){
      if([13,38,40].indexOf(evt.keyCode) < 0) {
        $(this).timepicker('hide');
      }
    }).on('changeTime', function(){
      $(this).removeClass('input-error');
    }).on('timeFormatError', function(){
      $(this).addClass('input-error');
    });

  })
</script>
@endpush
