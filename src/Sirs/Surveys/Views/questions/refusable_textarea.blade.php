<input type="hidden" name="{{$question->name}}" value="{{$context['response']->{$question->name} ?? ''}}"></input>
<span class="mutually-exclusive">
    <textarea 
      @if($question->disabled)
        disabled
      @endif
      @if($question->readonly)
        readonly
      @endif
      name="{{$question->name}}_field" 
      class="form-control {{$class ?? ''}}"
      @if($question->placeholder)
      placeholder="{{$question->placeholder}}" 
      @endif
      @if(method_exists($question, 'getMin') && $question->min)
      min="{{$question->min}}"
      @endif
      @if(method_exists($question, 'getMax') && $question->max)
      max="{{$question->max}}"
      @endif
      {{($question->required) ? ' required' : ''}}
    />{{($context['response']->{$question->name} !== null && $context['response']->{$question->name} != config('surveys.refusedValue', -77)) ? (string)$context['response']->{$question->name} : ''}}</textarea>
    <div class="checkbox">
        <label>
            <input id="beans" type="checkbox" name="{{$question->name}}_refused" class="exclusive" 
              @if($context['response']->{$question->name} == config('surveys.refusedValue', -77)) checked @endif
            />
            {{$question->refuseLabel}}
        </label>
    </div>
    <script>
      (function(){
        document.querySelector('[name="{{$question->name}}_field"]').addEventListener('change', function(evt){
          document.querySelector('[name={{$question->name}}]').value = evt.target.value;
        });
        document.querySelector('[name="{{$question->name}}_refused"]').addEventListener('change', function(evt){
          if(evt.target.checked){
            document.querySelector('[name={{$question->name}}]').value = {{config('surveys.refusedValue', config('surveys.refusedValue', -77))}};  
          }else{
            document.querySelector('[name={{$question->name}}]').value = null;  
          }
        });
      })()
    </script>
</span>