{{-- @extends('surveys::questions.question')

@section('answers')
  @include('surveys::questions.input', ['question'=>$renderable, 'context'=>$context])
@endsection --}}

@component('surveys::questions.question', compact('renderable', 'context'))
  @slot('answers')
    @include('surveys::questions.input', ['question'=>$renderable, 'context'=>$context])
  @endslot
@endcomponent