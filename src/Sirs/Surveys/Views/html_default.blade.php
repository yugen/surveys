<div id="{{$renderable->id}}" class="block-html{{($renderable->class) ? ' '.$renderable->class:''}}">
  {!! html_entity_decode($renderable->html) !!}
</div>