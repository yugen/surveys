@extends('questions.question')
@section('answers')
  <div class="input-group">
    @include('questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'number'])
    <span class="input-group-addon">{{$renderable->unit}}s</span>
  </div>
@endsection