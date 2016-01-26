<div 
  class="
    form-group 
    question-block
    {{$this->class or ''}}    
    @{{
      @if(count($response->errors[}}{{$this->name}}@{{]))
        'has-error'
      @endif 
    }}
  " 
  id="{{$this->id or ''}}"
>
  @if($this->questionText)
  <div class="question-text">{{$this->questionText}}</div>
  @endif

  @{{
    @if($response->errors['}}{{$this->name}}@{{']['required'])
      <div class="error">This question is required</div>
    @endif 
  }}

  <div class="question-answers">
    <input 
      type="text" 
      name="{{$this->name}}"
      {{($this->placeholder) ? ' placeholder=".$this->placeholder."' or ''}}"
      {{($this->required) ? ' required' : ''}}
    />
  </div>
</div>