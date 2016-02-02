<div class="conatiner-block likert-container">
  <p class="likert-prompt">{{$renderable->prompt}}</p>
  <table class="table table-striped">
    <tbody>
      @foreach($renderable->questions as $question)
      <tr class="likert-question">
        <td class="question-col question-text">
          {{$question->questionText}}
        </td>
        <td class="option-col">
        <div class="btn-group" role="group">
          @foreach($renderable->options as $option)
           {{$option->renderWith('options.btn_group_option_likert_option')}}
          @endforeach
        </div>          
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>