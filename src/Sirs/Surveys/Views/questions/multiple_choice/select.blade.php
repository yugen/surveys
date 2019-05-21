@component('surveys::questions.question', compact('renderable', 'context'))
  @slot('answers')
    <div class="row">
      <div class="question-answers col-sm-9">
        @include('surveys::questions.multiple_choice.select_input', ['question'=>$renderable, 'context'=>$context])
      </div>
    </div>
    <div class="col-sm-3">@include('survey::error', ['question'=>$renderable])</div>
  </div>
@endsection
