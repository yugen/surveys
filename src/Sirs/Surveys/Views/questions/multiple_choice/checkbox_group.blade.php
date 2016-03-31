@extends('questions.question')

@section('answers')
      <!-- <div class="btn-group" role="group" data-toggle="buttons"> -->
            @foreach($renderable->options as $option)
              <!-- <label class="btn btn-default @if($context['response']->{$renderable->name} == $option->value)active @endif"> -->
              <div class="checkbox">
              <label>
               <input 
                type="checkbox" 
                name="{{$option->name}}" 
                id="{{$option->name}}_{{$option->value}}" 
                autocomplete="off"
                @if($context['response']->{$option->name} == $option->value)
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
      <!-- </div> -->
@endsection