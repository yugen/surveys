<div id="{{ $renderable->id }}" class="very-special-class">
    <img src="https://laughingsquid.com/wp-content/uploads/tumblr_lugsodPWWM1qd6s2yo1_500.gif"></img>
    @if($renderable->contents)
      @foreach ($renderable->contents as $content)
        <div class="alert alert-danger">
            {!! $content->render($context) !!}
        </div>
      @endforeach
    @else
      <div class="alert-danger">This block does not have any contents</div>
    @endif    
</div>