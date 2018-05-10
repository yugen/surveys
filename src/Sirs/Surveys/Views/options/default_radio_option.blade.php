<div class="radio">
  <label>
    <input 
      type="radio" 
      name="{{$renderable->name}}" 
      id="{{$renderable->id}}"
      value="{{$renderable->value}}"{{($renderable->selected ? ' selected' : '')}}
    />
  {{$renderable->getCompiledQuestionText($context)}}
  </label>
</div>
