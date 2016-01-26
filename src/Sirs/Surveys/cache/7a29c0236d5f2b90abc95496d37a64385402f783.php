<dl>
  <dt>Name</dt>
  <dd><?php echo e($renderable->name); ?></dd>

  <dt>Id</dt>
  <dd><?php echo e($renderable->id); ?></dd>

  <dt>Class</dt>
  <dd><?php echo e($renderable->class); ?></dd>

  <?php echo('@if($response->errors[\'required\'])'."\n"); ?>
  <dt>Required</dt>
  <dd><?php echo e($renderable->required); ?></dd>
  <?php echo('@endif'."\n"); ?>

  <?php if($renderable->questionText): ?>
  <dt>Question Text</dt>
  <dd><?php echo e($renderable->questionText); ?></dd>
  <?php endif; ?>

  <dt>Data Format</dt>
  <dd><?php echo e($renderable->dataFormat); ?></dd>

  <dt>Value: </dt>
  <dd>{{$response->value}}</dd>
</dl>