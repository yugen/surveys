<label class="btn btn-primary active">
  <input 
    type="radio" 
    name="{{$renderable->name}}" 
    id="{{$renderable->id}}" 
    autocomplete="off" 
    class="{{ $option->class }}"
    {{($renderable->selected) ? 'checked' : ''}}
  >
    {!! $renderable->getCompiledLabel($context) !!}
</label>
