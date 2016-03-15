<div class="form-group question-block{{($renderable->class) ? ' '.$renderable->class : ''}}
  id="{{$renderable->id or ''}}"
>
  @if($renderable->questionText)
    <div class="question-text">{{$renderable->questionText}}</div>
  @endif
  <div class="question-answers">
    <div class="btn-group" role="group" data-toggle="buttons">
          @foreach($renderable->options as $option)
            <label class="btn btn-default @if($context['response']->{$renderable->name} == $option->value)active @endif">
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
  </div>
</div>  