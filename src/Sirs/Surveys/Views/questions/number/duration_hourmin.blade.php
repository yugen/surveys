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
    document.getElementById('{{$renderable->name}}_hours').addEventListener('change', function(evt){
      elem = document.getElementsByName('{{$renderable->name}}')[0];
      elem.value = ((parseInt(document.getElementById('{{$renderable->name}}_hours').value) * 60) || 0) + (parseInt(document.getElementById('{{$renderable->name}}_minutes').value) ||0);
    });

    document.getElementById('{{$renderable->name}}_minutes').addEventListener('change', function(evt){
        elem = document.getElementsByName('{{$renderable->name}}')[0];
        elem.value = ((parseInt(document.getElementById('{{$renderable->name}}_hours').value) * 60) || 0) + (parseInt(document.getElementById('{{$renderable->name}}_minutes').value) ||0);
    });
  </script>
 @endsection