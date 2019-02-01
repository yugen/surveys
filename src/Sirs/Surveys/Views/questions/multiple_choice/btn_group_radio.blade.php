<div class="btn-group btn-group-toggle" role="group" data-toggle="buttons">
  @foreach($question->options as $option)
    <label 
      id="{{$question->name}}-{{$option->name}}-label"
      class="btn btn-light border @if(!is_null($context['response']->{$question->name}) && $context['response']->{$question->name} == $option->value)active @endif
        @if($option->class){{$option->class}}@endif
      "
      @if(isset($fixedWidth))
        style="min-width: {{$fixedWidth/count($question->options)}}px"
      @endif
    >
     <input 
      type="radio" 
      class="{{ $option->class }}"
      name="{{$question->name}}" 
      id="{{$question->name}}_{{$option->value}}" 
      value="{{ $option->value }}"
      {{($question->required) ? ' required' : ''}}
      autocomplete="off"
      @if(!is_null($context['response']->{$question->name}) && $context['response']->{$question->name} == $option->value)
        checked="checked"
      @endif
      @if($option->show)
        data-skipTarget="{{$option->show}}"
      @endif
      @if($option->hide)
        data-hide="{{$option->hide}}"
      @endif
     />
     {{$option->label}}
   </label>
  @endforeach
</div>