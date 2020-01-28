<div class="conatiner-block likert-container {{$renderable->class ?? ''}}" id="{{$renderable->id ?? ''}}">
  <p class="likert-prompt">
    <strong>
      {!! html_entity_decode($renderable->getCompiledPrompt($context)) !!}
    </strong>
  </p>
  <table class="table table-striped table-sm">
    <thead>
      <th class="question-col">&nbsp;</th>
      @foreach($renderable->options as $option)
        <th class="option-col text-center" style="width: {{(1/$renderable->options->count()*50)}}%">
          {{$option->labelIsSet() ? $option->label : ''}}
        </th>
      @endforeach
    </thead>
    <tbody>
      @foreach($renderable->questions as $question)
      <tr class="likert-question">
        <td class="question-col question-text @if ($question->class) {{$question->class}} @endif">
          {!! html_entity_decode($question->getCompiledQuestionText($context)) !!}
        </td>
        @foreach($renderable->options as $option)
        <td class="option-col text-center">
          <input 
            type="radio" 
            name="{{$question->name}}" 
            id="{{$question->id.$option->value}}" 
            autocomplete="off" 
            class="{{ $option->class }}"
            value="{{$option->value}}" 
            @if($context['response']->{$question->name} == $option->value)
              checked="checked"
            @endif
            >
        </td>
        @endforeach
      </tr>
      @endforeach
    </tbody>
  </table>
</div>