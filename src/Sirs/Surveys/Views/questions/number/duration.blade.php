@extends('questions.question')
@section('answers')
  <!-- <div class="input-group"> -->
    @include('questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'text'])
    <!-- <span class="">{{$renderable->unit}}s</span> -->
  <!-- </div> -->
 @endsection