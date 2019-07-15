@component('surveys::questions.question', compact('renderable', 'context'))
  @slot('answers')
      @include('surveys::questions.multiple_choice.select_input', ['question'=>$renderable, 'context'=>$context])
  @endslot
@endcomponent