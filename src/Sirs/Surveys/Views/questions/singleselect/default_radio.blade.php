<div 
  class="form-group question-block{{($renderable->class) ? ' '.$renderable->class : ''}}
  id="{{$renderable->id or ''}}"
>
  @if($renderable->questionText)
  <div class="question-text">{{$renderable->questionText}}</div>
  @endif


  <div class="question-answers">
    <div class="btn-group" role="group">
          @foreach($renderable->options as $option)
           <input 
            type="radio" 
            name="{{$renderable->name}}" 
            id="{{$renderable->name}}_{{$option->value}}" 
            value="{{ $option->value }}"
            {{($renderable->required) ? ' required' : ''}}

           /> <label for="{{$renderable->name}}_{{$option->value}}"> {{$option->label}}</label>
          @endforeach
    </div> 
  </div>
</div>
