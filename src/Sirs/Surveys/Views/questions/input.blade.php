@if($question->refusable)
  @include('questions.refusable_input', ['question'=>$question])
@else
<input 
  type="{{$type or 'text'}}" 
  name="{{$question->name}}" 
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
  value="{{($context['response']->{$question->name} !== null) ? (string)$context['response']->{$question->name} : ''}}"
/>
@endif
