<div 
  class="form-group row question-block{{($renderable->class) ? ' '.$renderable->class : ''}}"
  @if($renderable->id)
  id="{{$renderable->id}}"
  @endif
>
  <div class="question-answers col-sm-9">
    @if($renderable->questionText)
    <div class="question-text">{{$renderable->questionText}}</div>
    @endif
    <input 
      type="text" 
      name="{{$renderable->name}}" 
      class="form-control"
      @if($renderable->placeholder)
      placeholder="{{$renderable->placeholder}}" 
      @endif
      {{($renderable->required) ? ' required' : ''}}
      value="{{$context['response']->{$renderable->name} or ''}}"
    />
  </div>
  <div class="col-sm-3">  @include('error')</div>
</div>
