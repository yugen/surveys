@component('surveys::questions.question', compact('context', 'renderable'))

@slot('answers')
      <div class="btn-group btn-group-toggle  btn-group-sm" role="group" data-toggle="buttons">
        @foreach($renderable->options as $option)
          <label class="btn btn-light border btn-default @if($context['response']->{$renderable->name} == $option->value)active @endif  {{ $option->class }}">
           <input 
            type="radio" 
            name="{{$renderable->name}}" 
            id="{{$renderable->name}}_{{$option->value}}" 
            value="{{ $option->value }}"
            class="{{ $option->class }}"
            {{($renderable->required) ? ' required' : ''}}
            autocomplete="off"
            @if($context['response']->{$renderable->name} == $option->value)
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
@endslot

@endcomponent