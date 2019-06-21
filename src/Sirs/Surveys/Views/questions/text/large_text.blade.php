@component('surveys::questions.question', compact('renderable', 'context'))
  @slot('answers')
    @include('surveys::questions.textarea', ['question'=>$renderable, 'context'=>$context])
  @endslot
@endcomponent