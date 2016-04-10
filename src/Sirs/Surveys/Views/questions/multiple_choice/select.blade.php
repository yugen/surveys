<div 
  class="
    form-group question-block
    {{($renderable->class) ? ' '.$renderable->class : ''}} 
    @if(isset($context['errors']) && $context['errors']->has($renderable->name)) has-errors @endif
  "
  id="{{$renderable->id or ''}}"
>
  @if($renderable->questionText)
    <div class="question-text">{{$renderable->questionText}}</div>
  @endif
  <div class="row">
    <div class="question-answers col-sm-9">
      @include('questions.multiple_choice.select_input', ['question'=>$renderable, 'context'=>$context])
    </div>
    <div class="col-sm-3">@include('error', ['question'=>$renderable])</div>
  </div>
</div>  