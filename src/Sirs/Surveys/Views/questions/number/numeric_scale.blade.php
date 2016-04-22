@extends('questions.question')

@section('answers')
  @if($renderable->refusable)
  <div class="mutually-exclusive">
    <div class="clearfix">
  @endif
  <div class="pull-left" id="{{$renderable->name}}-buttons">
    <table width="100%" class="text-muted">
      <tr>
        @foreach($renderable->legend as $idx => $item)
          <td class="
            @if($idx == 0) text-left @elseif($idx == count($renderable->legend)-1) text-right @else text-center @endif"
            style="width: {{((1/count($renderable->legend))*100)}}%"
          >
            {{$item['label']}}
          </td>
        @endforeach
      </tr>
    </table>
    @include('questions.multiple_choice.btn_group_radio', ['question'=>$renderable, 'context'=>$context, 'fixedWidth'=>600])
  </div>
  @if($renderable->refusable)
    </div>
    <div class="radio">
      <label>
        <input type="radio" 
          id="{{$renderable->name}}-refused" 
          name="{{$renderable->name}}" 
          class="exclusive" 
          value="-77"
          @if($context['response']->{$renderable->name} == -77)checked @endif></input>
        Refuse
      </label>
    </div>
  </div>
  <script>
    (function(){
      document.getElementById('{{$renderable->name}}-refused').addEventListener('change', function(evt){
        var label = document.querySelector('#{{$renderable->name}}-buttons label.active').classList.remove('active');
      })
    })()
  </script>
  @endif
@endsection