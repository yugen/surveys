@extends('chrome')

<form>
@section('content')
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
      @if(true)
        <button type="submit" class="btn btn-default">Back</button>
      @endif
      @if(true)
      <button type="submit" class="btn btn-primary">Next</button>
      @endif
      @if(true)
        <button type="submit" class="btn btn-primary">Finish &amp; Finalize</button>
      @endif
      @if(true)
        <button type="submit" class="btn btn-default pull-right">Save</button>
      @endif
    </div>
  </div>
@endsection
</form>