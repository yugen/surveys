<div 
  class="
    form-group question-block
    {{($renderable->class) ? ' '.$renderable->class : ''}} 
    @if(isset($context['errors']) && $context['errors']->has($renderable->name)) has-errors @endif
  "
  id="{{$renderable->id or ''}}"
>
  @if($renderable->questionText)
    <div class="question-text">{{$renderable->questionText}}</div>
  @endif
  <div class="row">
    <div class="question-answers col-sm-9">
      <select name="{{$renderable->name}}" id="{{$renderable->id}}"
        class=" form-control
        @if($renderable->class)
          {{$renderable->class}}
        @endif
        "
        {{($renderable->required) ? ' required' : ''}}
      >
            @foreach($renderable->options as $option)
              <option 
                id="{{$renderable->name}}_{{$option->value}}" 
                value="{{ $option->value }}"
                
                autocomplete="off"
                @if($context['response']->{$renderable->name} == $option->value)
                  selected="selected"
                @endif
                @if($option->show)
                  data-skipTarget="{{$option->show}}"
                @endif
                @if($option->hide)
                  data-hide="{{$option->hide}}"
                @endif
               />
               {{$option->label}}
             </option>
            @endforeach
      </select> 
    </div>
    <div class="col-sm-3">@include('error', ['question'=>$renderable])</div>
  </div>
</div>  