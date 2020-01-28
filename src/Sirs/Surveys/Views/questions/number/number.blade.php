@component('surveys::questions.question', compact('renderable', 'context'))

  @slot('answers')
    @include('surveys::questions.input', ['type'=>'text', 'question'=>$renderable, 'context'=>$context])
  @endslot

@endcomponent