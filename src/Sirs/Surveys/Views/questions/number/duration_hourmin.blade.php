@extends('questions.question')
@section('answers')
  <div class="input-group">
    <input type="text" id="{{$renderable->name}}_hours" 
      class="form-control"
      @if(method_exists($renderable, 'getMin') && $renderable->min)
        min="{{$renderable->min / 60}}"
      @endif
      @if(method_exists($renderable, 'getMax') && $renderable->max)
        max="{{floor($renderable->max / 60)}}"
      @endif
      {{($renderable->required) ? ' required' : ''}}
      @if($context['response']->{$renderable->name} != -77 && 
          $context['response']->{$renderable->name} !== null &&
          $context['response']->{$renderable->name} !== '')
        value="{{($context['response']->{$renderable->name} !== null) ? (floor($context['response']->{$renderable->name}/60)) : '' }}"
      @endif
    />
    <span class="input-group-addon">hours</span>
    <input type="text"  id="{{$renderable->name}}_minutes" 
      class="form-control"
      @if(method_exists($renderable, 'getMin') && $renderable->min)
      min="{{$renderable->min % 60}}"
      @endif
    @if(method_exists($renderable, 'getMax') && $renderable->max)
      max="{{59}}"
      @endif
      {{($renderable->required) ? ' required' : ''}}
       @if($context['response']->{$renderable->name} != -77 && 
          $context['response']->{$renderable->name} !== null &&
          $context['response']->{$renderable->name} !== '' )
          value="{{ ($context['response']->{$renderable->name} !== null) ? ($context['response']->{$renderable->name} % 60) : '' }}"
        @endif
    />
    <span class="input-group-addon">minutes</span>
  </div>
  @include('questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'hidden'])
  <script>
    // in a self-invoking function to constrain scope
    (function(){
      var hiddenInput = document.getElementsByName('{{$renderable->name}}')[0],
          hoursInput = document.getElementById('{{$renderable->name}}_hours'),
          minInput = document.getElementById('{{$renderable->name}}_minutes');
      var setInput = function(){
        if (hoursInput.value != '' || minInput.value != '') {
          hiddenInput.value = ((parseInt(hoursInput.value) * 60) || 0) + (parseInt(minInput.value) || 0);
        }else{
          hiddenInput.value = null;
        }
      }
      hoursInput.addEventListener('change', function(evt){
        setInput();
      });

      minInput.addEventListener('change', function(evt){
        setInput();
      });
    })()
  </script>
 @endsection