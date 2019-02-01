@if($question->refusable)
  @include('surveys::questions.refusable_textarea', ['question'=>$question])
@else
  <textarea 
    name="{{$question->name}}" 
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
  />{{($context['response']->{$question->name} !== null) ? (string)$context['response']->{$question->name} : ''}}</textarea>
@endif