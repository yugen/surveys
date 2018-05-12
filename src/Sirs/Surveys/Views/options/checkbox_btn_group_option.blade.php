<label class="btn btn-primary active">
  <input 
    type="checkbox" 
    name="{{$renderable->name}}" 
    id="{{$renderable->id}}" 
    autocomplete="off" 
    {{($renderable->selected) ? 'checked' : ''}}
  >
    {!! $renderable->getCompiledLabel($context) !!}
</label>
