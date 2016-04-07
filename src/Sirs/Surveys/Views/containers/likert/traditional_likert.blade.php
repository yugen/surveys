<div class="conatiner-block likert-container">
  <p class="likert-prompt">{{$renderable->prompt}}</p>
  <table class="table table-striped">
    <thead>
      <th class="question-col">&nbsp;</th>
      @foreach($renderable->options as $option)
      <th class="option-col">{{$option->label}}</th>
      @endforeach
    </thead>
    <tbody>
      @foreach($renderable->questions as $question)
      <tr class="likert-question">
        <td class="question-col question-text">
          {!! html_entity_decode($question->getCompiledQuestionText($context)) !!}
        </td>
        @foreach($renderable->options as $option)
        <td class="option-col">
          {{$option->renderWith('options.traditional_likert_option')}}
        </td>
        @endforeach
      </tr>
      @endforeach
    </tbody>
    <tfoot>
      <th class="question-col">&nbsp;</th>
      @foreach($renderable->options as $option)
      <th class="option-col">{{$option->label}}</th>
      @endforeach
    </tfoot>
  </table>
</div>