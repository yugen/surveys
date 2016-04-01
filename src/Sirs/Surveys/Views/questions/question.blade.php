<div 
  class="
    form-group question-block
    {{($renderable->class) ? ' '.$renderable->class : ''}} 
    @if(isset($context['errors']) && $context['errors']->has($renderable->name)) has-errors @endif
  "
  id="{{$renderable->id or ''}}"
>
  @if(preg_match('/form-inline/', $renderable->class))
    <label>{{$renderable->questionText}}</label>
    @yield('answers')
    @include('error', ['question'=>$renderable])
  @else
    @if($renderable->questionText)
      <div class="question-text">{{$renderable->questionText}}</div>
    @endif
    <div class="row">
      <div class="question-answers col-sm-9">
        @yield('answers')
      </div>
      <div class="col-sm-3">@include('error', ['question'=>$renderable])</div>
    </div>
  @endif
</div>  