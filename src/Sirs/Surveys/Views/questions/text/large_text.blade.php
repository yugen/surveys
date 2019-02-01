@extends('surveys::questions.question')

@section('answers')
  @include('surveys::questions.textarea', ['question'=>$renderable, 'context'=>$context])
@endsection
