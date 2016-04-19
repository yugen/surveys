@extends('questions.question')
@section('answers')
  <div class="input-group">
      
    <input type="number" placeholder="0" id="{{$renderable->name}}_hours" 
      class="form-control"
      @if(method_exists($renderable, 'getMin') && $renderable->min)
      min="{{$renderable->min / 60}}"
    @endif
    @if(method_exists($renderable, 'getMax') && $renderable->max)
      max="{{floor($renderable->max / 60)}}"
    @endif
    {{($renderable->required) ? ' required' : ''}}
      value="{{(floor($context['response']->{$renderable->name}/60)) }}"
    />
    <span class="input-group-addon" >hours</span>
    <input type="number" placeholder="0" id="{{$renderable->name}}_minutes" 
      class="form-control"
      @if(method_exists($renderable, 'getMin') && $renderable->min)
      min="{{$renderable->min % 60}}"
      @endif
    @if(method_exists($renderable, 'getMax') && $renderable->max)
      max="{{59}}"
      @endif
      {{($renderable->required) ? ' required' : ''}}
        value="{{($context['response']->{$renderable->name}%60) }}"
    />
    <span class="input-group-addon" >minutes</span>
    @include('questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'hidden'])
  </div>
  <script>
    document.getElementById('{{$renderable->name}}_hours').addEventListener('change', function(evt){
      elem = document.getElementsByName('{{$renderable->name}}')[0];
      elem.value = (parseInt(document.getElementById('{{$renderable->name}}_hours').value) * 60) + parseInt(document.getElementById('{{$renderable->name}}_minutes').value);
      //elem.fireEvent("onchange");

        
    });

  document.getElementById('{{$renderable->name}}_minutes').addEventListener('change', function(evt){
      elem = document.getElementsByName('{{$renderable->name}}')[0];
      elem.value = (parseInt(document.getElementById('{{$renderable->name}}_hours').value) * 60) + parseInt(document.getElementById('{{$renderable->name}}_minutes').value);
      //elem.fireEvent("onchange");

        
    });
  </script>
 @endsection