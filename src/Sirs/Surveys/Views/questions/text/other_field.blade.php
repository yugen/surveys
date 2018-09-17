<span 
  class="form-inline form-group question-block{{($renderable->class) ? ' '.$renderable->class : ''}}"
  @if($renderable->id)
  id="{{$renderable->id}}"
  @endif
>
  @if($renderable->questionText)
  <label class="question-text">
    {!! html_entity_decode($question->getCompiledQuestionText($context)); !!}
  </label>
  @endif
  <input 
    type="text" 
    name="{{$renderable->name}}" 
    class="form-control"
    @if($renderable->placeholder)
      placeholder="{{$renderable->placeholder}}" 
    @endif
    value="{{$context['response']->{$renderable->name} ?? ''}}"
  />
</span>
