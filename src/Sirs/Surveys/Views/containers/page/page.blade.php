@extends('chrome')

@section('content')
<form class="sirs-survey" method="POST" name="{{$context['survey']['name']}}-{{$renderable->name}}">
  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4>
        {{ucwords($context['survey']['name'])}}
        v.{{$context['survey']['version']}}
        - {{$renderable->name}}
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
        <button type="submit" name="nav" value="prev" class="btn btn-default">Back</button>
      @endif
      @if($context['survey']['currentPageIdx'] < ($context['survey']['totalPages']-1))
      <button type="submit" name="nav" value="next" class="btn btn-primary">Next</button>
      @endif
      @if($context['survey']['currentPageIdx'] == ($context['survey']['totalPages']-1))
        <button type="submit" name="nav" value="finalize" class="btn btn-primary">Finish &amp; Finalize</button>
      @endif
      @if(true)
        <button type="submit" name="nav" value="save" class="btn btn-default pull-right">Save</button>
      @endif
    </div>
  </div>
</form>
@endsection
