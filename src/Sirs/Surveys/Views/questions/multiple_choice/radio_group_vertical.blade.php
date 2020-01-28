@component('surveys::questions.question', compact('renderable', 'context'))
  @slot('answers')
        <div class="btn-group-vertical" role="group">
          @foreach($renderable->options as $option)
            <label class="@if($context['response']->{$renderable->name} == $option->value)active @endif" style="text-align: left">
            @component(
              'surveys::questions.multiple_choice.primitives.radio', 
              [
                'question'=>$renderable, 
                'option'=>$option, 
                'context'=>$context
              ]
            )
            @endcomponent
            {{$option->label}}
          </label>
          @endforeach
        </div>
  @endslot
@endcomponent
