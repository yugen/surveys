<input type="hidden" name="{{$question->name}}" value="{{$context['response']->{$question->name} or ''}}"></input>
<span class="mutually-exclusive">
    <textarea 
      name="{{$question->name}}_field" 
      class="form-control {{$class or ''}}"
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
    />{{($context['response']->{$question->name} !== null && $context['response']->{$question->name} != -77) ? (string)$context['response']->{$question->name} : ''}}</textarea>
    <div class="checkbox">
        <label>
            <input id="beans" type="checkbox" name="{{$question->name}}_refused" class="exclusive" 
              @if($context['response']->{$question->name} == -77) checked @endif
            />
            Refused
        </label>
    </div>
    <script>
      (function(){
        document.querySelector('[name="{{$question->name}}_field"]').addEventListener('change', function(evt){
          document.querySelector('[name={{$question->name}}]').value = evt.target.value;
        });
        document.querySelector('[name="{{$question->name}}_refused"]').addEventListener('change', function(evt){
          if(evt.target.checked){
            document.querySelector('[name={{$question->name}}]').value = -77;  
          }else{
            document.querySelector('[name={{$question->name}}]').value = null;  
          }
        });
      })()
    </script>
</span>