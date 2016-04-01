@extends('questions.question')

@section('answers')
  @include('questions.input', ['type'=>'number', 'question'=>$renderable, 'context'=>$context])
@endsection