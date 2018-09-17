@extends('questions.question')

@section('answers')
  @foreach($renderable->options as $option)
    <div class="checkbox {{$option->class}}">
    <label>
     <input type="checkbox" 
      name="{{ $option->name }}"
      id="{{ $option->name }}_checkbox" 
      class="{{ $option->class}}"
      autocomplete="off"
      value="1"
  }
      @if( isset($context['response']->{$option->name}) )
        checked="checked"
      @endif
      @if($option->show)
        data-skipTarget="{{$option->show}}"
      @endif
      @if($option->hide)
        data-hide="{{$option->hide}}"
      @endif
      @if($option->class)
        class="{{$option->class}}"
      @endif
      />
     {{$option->label}}
   </label>
   </div>
  @endforeach
@endsection

@section('errors')
  @if ( isset($context['errors']) 
        && count(array_intersect( array_keys($context['errors']->getMessages()), $renderable->getOptionNames() )) > 0 )
    <div class="error-block">
      <ul class="error-list list-unstyled">
        <li>A response is required for this question</li>
      </ul>
    </div>
  @endif
@endsection