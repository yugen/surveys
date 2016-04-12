@extends('questions.question')

@section('answers')
  @foreach($renderable->options as $option)
    <div class="checkbox">
    <label>
     <input type="checkbox" 
      name="{{$option->name}}"
      id="{{$option->name}}_checkbox" 
      autocomplete="off"
      value="1"
      @if(isset($context['response']->{$option->name}))
        checked="checked"
      @endif
      @if($option->show)
        data-skipTarget="{{$option->show}}"
      @endif
      @if($option->hide)
        data-hide="{{$option->hide}}"
      @endif
     />
     {{$option->label}}
   </label>
   </div>
  @endforeach
@endsection