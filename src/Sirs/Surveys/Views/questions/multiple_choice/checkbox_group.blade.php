@extends('questions.question')

@section('answers')
  @foreach($renderable->options as $option)
    <div class="checkbox">
    <label>
     <input type="checkbox" 
      name="{{$renderable->name}}_{{snake_case($option->label)}}"
      id="{{$renderable->name}}_{{snake_case($option->label)}}_checkbox" 
      autocomplete="off"
      value="1"
  }
      @if(isset($context['response']->{snake_case($option->label)}))
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