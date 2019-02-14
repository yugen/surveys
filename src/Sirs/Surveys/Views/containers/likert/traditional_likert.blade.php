<div class="conatiner-block likert-container">
  <p class="likert-prompt"><strong>{{$renderable->prompt}}</strong></p>
  <table class="table table-striped table-sm">
    <thead>
      <th class="question-col" style="width: 50%">&nbsp;</th>
      @foreach($renderable->options as $option)
        <th class="option-col text-center" style="width: {{(1/$renderable->options->count()*50)}}%">
          {{$option->labelIsSet() ? $option->label : ''}}
        </th>
      @endforeach
    </thead>
    <tbody>
      @foreach($renderable->questions as $question)
      <tr class="likert-question">
        <td class="question-col question-text">
          {!! html_entity_decode($question->getCompiledQuestionText($context)) !!}
        </td>
        @foreach($renderable->options as $option)
        <td class="option-col text-center">
          <input type="radio" name="{{$renderable->name}}" value="{{$option->value}}">
        </td>
        @endforeach
      </tr>
      @endforeach
    </tbody>
    {{-- <tfoot>
      <th class="question-col">&nbsp;</th>
      @foreach($renderable->options as $option)
        <th class="option-col text-center" style="width: {{(1/$renderable->options->count()*50)}}%">
          {{$option->labelIsSet() ? $option->label : ''}}
        </th>
      @endforeach
    </tfoot> --}}
  </table>
</div>