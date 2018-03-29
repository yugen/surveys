<div class="container-block checkbox-container">
  <p class="checkbox-prompt">{{$renderable->prompt}}</p>
  <table class="table table-striped">
    <tbody>
      @foreach($renderable->questions as $question)
      <tr class="checkbox-question">
        <td class="question-col question-text">
          {{$question->questionText}}
        </td>
        <td class="option-col">
        <div class="btn-group" role="group">
          @foreach($renderable->options as $option)
           {{$option->renderWith('options.checkbox_btn_group_option_option')}}
          @endforeach
        </div>          
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>