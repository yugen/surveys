@extends('questions.question')

@section('answers')
  @include('questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'text', 'class'=>"date-picker"])
@endsection