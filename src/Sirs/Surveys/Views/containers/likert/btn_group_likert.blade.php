<!-- <?php print('<pre>');print_r($renderable->getOptions());print('</pre>'); ?> -->

<div class="conatiner-block likert-container">
  <table class="table table-striped">
    <thead>
      <th class="likert-prompt" colspan="3">
        {!! html_entity_decode($renderable->prompt !!}
      </th>
    </thead>
    <tbody>
      @foreach($renderable->questions as $question)
      <tr class="likert-question
         {{($question->class) ? ' '.$question->class : ''}}
         @if(isset($context['errors']) && $context['errors']->has($question->name)) has-errors @endif"
        id="{{$question->id or ''}}"
      >
        <td class="question-col question-text">
          {{$question->questionText}}
          <div>@include('error', ['question'=>$question])</div>
        </td>
        <td class="option-col">
          <div class="btn-group" role="group" data-toggle="buttons">
            @foreach($renderable->options as $option)
                <label class="btn btn-default btn-sm @if($context['response']->{$question->name} == $option->value)active @endif">
                 <input 
                  type="radio" 
                  name="{{$question->name}}" 
                  id="{{$question->name}}_{{$option->value}}" 
                  value="{{ $option->value }}"
                  autocomplete="off"
                  @if($context['response']->{$question->name} == $option->value)
                    checked="checked"
                  @endif
                  {{($question->required) ? ' required' : ''}}
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
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>