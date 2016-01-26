<dl>
  <dt>Name</dt>
  <dd>{{$renderable->name}}</dd>

  <dt>Id</dt>
  <dd>{{$renderable->id}}</dd>

  <dt>Class</dt>
  <dd>{{$renderable->class}}</dd>

  <?php echo('@if($response->errors[\'required\'])'."\n"); ?>
  <dt>Required</dt>
  <dd>{{$renderable->required}}</dd>
  <?php echo('@endif'."\n"); ?>

  @if($renderable->questionText)
  <dt>Question Text</dt>
  <dd>{{$renderable->questionText}}</dd>
  @endif

  <dt>Data Format</dt>
  <dd>{{$renderable->dataFormat}}</dd>

  <dt>Value: </dt>
  <dd>@{{$response->value}}</dd>
</dl>