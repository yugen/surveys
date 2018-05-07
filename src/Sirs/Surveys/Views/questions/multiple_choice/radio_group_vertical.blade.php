@extends('questions.question')

@section('answers')
      <div class="btn-group-vertical" role="group" data-toggle="buttons">
        @foreach($renderable->options as $option)
          <label class="@if($context['response']->{$renderable->name} == $option->value)active @endif" style="text-align: left">
           <input 
            type="radio" 
            name="{{$renderable->name}}" 
            id="{{$renderable->name}}_{{$option->value}}" 
            value="{{ $option->value }}"
            {{($renderable->required) ? ' required' : ''}}
            autocomplete="off"
            @if($context['response']->{$renderable->name} == $option->value)
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
        @endforeach
      </div>
@endsection