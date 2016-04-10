<select name="{{$question->name}}" id="{{$question->id}}"
  class=" form-control
  @if($question->class)
    {{$question->class}}
  @endif
  "
  {{($question->required) ? ' required' : ''}}
>
      @foreach($question->options as $option)
        <option 
          id="{{$question->name}}_{{$option->value}}" 
          value="{{ $option->value }}"
          
          autocomplete="off"
          @if($context['response']->{$question->name} == $option->value)
            selected="selected"
          @endif
          @if($option->show)
            data-skipTarget="{{$option->show}}"
          @endif
          @if($option->hide)
            data-hide="{{$option->hide}}"
          @endif
         />
         {{$option->label}}
       </option>
      @endforeach
</select>