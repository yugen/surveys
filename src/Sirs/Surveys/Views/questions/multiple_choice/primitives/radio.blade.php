<input 
    type="radio" 
    name="{{$question->name}}" 
    id="{{$question->name}}_{{$option->value}}" 
    value="{{ $option->value }}"
    class="{{ $option->class }}"
    {{($question->required) ? ' required' : ''}}
    autocomplete="off"
    @if($context['response']->{$question->name} == $option->value)
    checked="checked"
    @endif
    @if($option->show)
    data-skipTarget="{{$option->show}}"
    @endif
    @if($option->hide)
    data-hide="{{$option->hide}}"
    @endif
/>
