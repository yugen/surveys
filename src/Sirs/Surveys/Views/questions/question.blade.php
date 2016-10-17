<div 
  class="form-group question-block {{($renderable->class) ? $renderable->class : ''}} @if(isset($context['errors']) && $context['errors']->has($renderable->name)) has-errors @endif"
  @if($renderable->id)id="{{$renderable->id}}"@endif
>
  @if(preg_match('/form-inline/', $renderable->class))
    <label>  
      {!! html_entity_decode($renderable->getCompiledQuestionText($context)); !!}
    </label>
    @yield('answers')
    <div class="pull-right col-sm-3">
      @include('error', ['question'=>$renderable])
    </div>
  @else
    @if($renderable->questionText)
      @include('questions.question_text', ['question'=>$renderable])
    @endif

    <div class="row">
      <div class="question-answers col-sm-9">
        @yield('answers')
      </div>
    
      <div class="col-sm-3">
        @yield('errors', View::make('surveys::error', ['question'=>$renderable]))
      </div>
    
    </div>

  @endif
</div>  