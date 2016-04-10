<input type="hidden" name="{{$question->name}}" value="{{$context['response']->{$question->name} or ''}}"></input>
<div class="mutually-exclusive">
    <input 
      type="{{$type or 'text'}}" 
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
      @if(isset($type) && $type == 'number')
      step="any"
      @endif
      {{($question->required) ? ' required' : ''}}
      value="{{$context['response']->{$question->name} or ''}}"
    />
    <div class="checkbox">
        <label>
            <input id="beans" type="checkbox" name="{{$question->name}}_refused" class="exclusive" />
            Refused
        </label>
    </div>
    <script>
      document.querySelector('[name="{{$question->name}}_field"]').addEventListener('change', function(evt){
        document.querySelector('[name={{$question->name}}]').value = event.target.value;
        console.log(document.querySelector('[name={{$question->name}}]').value);
      });
      document.querySelector('[name="{{$question->name}}_refused"]').addEventListener('change', function(evt){
        if(event.target.checked){
          document.querySelector('[name={{$question->name}}]').value = -77;  
        }else{
          document.querySelector('[name={{$question->name}}]').value = null;  
        }
        console.log(document.querySelector('[name={{$question->name}}]').value);
      });
      document.querySelector('[name={{$question->name}}]').addEventListener('change', function(evt){
        console.log('hidden value: '+evt.target.value);
      })
    </script>
</div>