<div 
  class="form-group question-block{{($renderable->class) ? ' '.$renderable->class : ''}}
  id="{{$renderable->id or ''}}"
>
  @if($renderable->questionText)
  <div class="question-text">{{$renderable->questionText}}</div>
  @endif


  <div class="question-answers">
    <input 
      type="text" 
      name="{{$renderable->name}}" 
      placeholder="{{$renderable->placeholder or ''}}{{($renderable->required) ? ' required' : ''}}" 
    />
  </div>
</div>
