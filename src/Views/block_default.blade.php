<div id="{{$renderable->id}}">
@if($renderable->contents)
  @foreach ($renderable->contents as $content)
    <div>{!! $content->render($context) !!}</div>
  @endforeach
@else
  <div class="alert-danger">This block does not have any contents</div>
@endif
</div>