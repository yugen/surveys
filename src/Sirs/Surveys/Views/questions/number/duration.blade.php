@extends('questions.question')
@section('answers')
  <div class="input-group">
    @include('questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'text'])
    <span class="input-group-addon">{{$renderable->unit}}s</span>
  </div>
 @endsection