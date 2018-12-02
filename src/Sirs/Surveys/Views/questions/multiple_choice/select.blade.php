@extends('questions.question')

@section('answers')
  <div class="row">
    <div class="question-answers col-sm-9">
      @include('questions.multiple_choice.select_input', ['question'=>$renderable, 'context'=>$context])
    </div>
    <div class="col-sm-3">@include('survey::error', ['question'=>$renderable])</div>
  </div>
@endsection
