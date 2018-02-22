@extends('questions.question')

@section('answers')
  @include('questions.input', ['type'=>'text', 'question'=>$renderable, 'context'=>$context])
@endsection