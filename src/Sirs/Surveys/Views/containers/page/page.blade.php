@extends('layouts.app')

@section('content')

<form class="sirs-survey" method="POST" name="{{$context['survey']['name']}}-{{$renderable->name}}" novalidate>
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right" style="margin-top: 4px;">
        
        @if($context['response']->finalized_at)
          <small class="finalized-response-warning">
            <strong>This response was finalized on {{$context['response']->finalized_at->format('m/d/Y')}}</strong>
          </small>
        @endif

        <a href="{{route('surveys.responses.show', [$context['survey']['object']->slug, $context['response']->id])}}" class="btn btn-sm btn-default">View Data</a>
      </div>
      <h4>
        {{$context['response']->respondent->full_name ?? 'Respondent:'.$context['response']->respondent->id}} 
        - 
        {{ $context['survey']['title'] ?? ucwords($context['survey']['name'])}}        
        - {{$renderable->title}}
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
<div class="alert alert-info notification" id="flast-notification">Auto-saved at <span id="notification-time"></span>.</div>
<div class="text-muted">
  <small>
    {{ $context['survey']['title'] ?? ucwords($context['survey']['name'])}}        
    - {{$renderable->title}}
    version {{$context['survey']['version']}}
  </small>
</div>
@endsection

@push('styles')
  <style>
    .notification{
      position: fixed;
      top: 20px;
      right: 20px;
      display: none;
      margin: auto;
    }
  </style>
@endpush

@push('scripts')
<script>

  // Plugins
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
  });

  @if(config('surveys.autosave.enabled'))
  // autosave
  $(document).ready(function(){

    var activeRequest = false;

    var formIsDirty = false;
    $('form.sirs-survey').on('change', function(){ formIsDirty = true; });

    var notify = function(){
      $('#notification-time').text(moment().format('hh:mm:ss'))
      $('#flast-notification').fadeIn();
      setTimeout( function(){$('#flast-notification').fadeOut()}, {{config('surveys.autosave.notify_time', 2500) }});
    }

    var autosave = function(){
      if(!activeRequest && formIsDirty){
        activeRequest = true;
        $.ajax({
          url: '{{route('surveys.autosave', [ get_class($context['respondent']),  $context['respondent']->id,  $context['survey']['object']->slug,  $context['response']->id ])}}',
          method: 'PUT',
          data: $('form.sirs-survey').serializeArray(),
          success: function(){
            activeRequest = formIsDirty = false;
            @if(config('surveys.autosave.notify'))
            notify();
            @endif
          },
          error: function(xhr, error){
            activeRequest = formIsDirty = false;
          }
        })    
      }
    };

    setInterval(autosave, {{config('surveys.autosave.frequency', 10000)}});
  });
  @endif
</script>
@endpush
