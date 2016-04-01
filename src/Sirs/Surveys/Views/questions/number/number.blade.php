@extends('questions.question')

@section('answers')
    <input 
      type="number" 
      name="{{$renderable->name}}" 
      class="form-control"
      @if($renderable->placeholder) placeholder="{{$renderable->placeholder}}" @endif
      {{($renderable->required) ? ' required' : ''}}
      value="{{$context['response']->{$renderable->name} or ''}}"
      @if($renderable->min) min="{{$renderable->min}}" @endif
      @if($renderable->max) max="{{$renderable->max}}" @endif
    />
@endsection