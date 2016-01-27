<div 
  class="form-group question-block{{($renderable->class) ? ' '.$renderable->class : ''}}
  <?= '@if( count($response->errors[\''.$renderable->name.'\']) > 0 )' ?> has-error <?= '@endif' ?>" 
  id="{{$renderable->id or ''}}"
>
  @if($renderable->questionText)
  <div class="question-text">{{$renderable->questionText}}</div>
  @endif

  <?= '@if(\'$response->errors[\''.$renderable->name.'\'][\'required\'])' ?><div class="error">This question is required</div><?= '@endif' ?>

  <div class="question-answers">
    <input type="text" name="{{$renderable->name}}" placeholder="{{$renderable->placeholder or ''}}{{($renderable->required) ? ' required' : ''}} />
  </div>
</div>
