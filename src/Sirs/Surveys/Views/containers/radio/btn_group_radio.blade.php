<div class="conatiner-block radio-container">
  <p class="radio-prompt">{{$renderable->prompt}}</p>
  <table class="table table-striped">
    <tbody>
      @foreach($renderable->questions as $question)
      <tr class="radio-question">
        <td class="question-col question-text">
          {{$question->questionText}}
        </td>
        <td class="option-col">
        @foreach($renderable->options as $option)        
          {{$option->renderWith('options.tradional_likert_option')}}        
        @endforeach
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>