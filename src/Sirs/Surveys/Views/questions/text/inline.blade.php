<div 
  class="form-inline form-group question-block{{($renderable->class) ? ' '.$renderable->class : ''}}"
  @if($renderable->id)
  id="{{$renderable->id}}"
  @endif
>
  @if($renderable->questionText)
  <label class="question-text">{{$renderable->questionText}}</label>
  @endif
  <input 
    type="text" 
    name="{{$renderable->name}}" 
    class="form-control"
    placeholder="{{$renderable->placeholder or ''}}" {{($renderable->required) ? ' required' : ''}}
    value="{{$context['response']->{$renderable->name} or ''}}"
  />
</div>