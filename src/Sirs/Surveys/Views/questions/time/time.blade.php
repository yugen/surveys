@component('surveys::questions.question', compact('renderable', 'context'))

  @slot('answers')
    @include('surveys::questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'text', 'class'=>"timepicker"])
  @endslot

@endcomponent