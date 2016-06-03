@extends('questions.question')

@section('answers')
  @include('questions.textarea', ['question'=>$renderable, 'context'=>$context])
@endsection
