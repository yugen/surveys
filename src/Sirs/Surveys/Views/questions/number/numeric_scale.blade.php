@extends('questions.question')

@section('answers')
  <div class="pull-left">
    <table width="100%" class="text-muted">
      <tr>
        @foreach($renderable->legend as $idx => $item)
          <td class="
            @if($idx == 0)
              text-left
            @elseif($idx == count($renderable->legend)-1)
              text-right
            @else
              text-center
            @endif"
          >
            {{$item['label']}}
          </td>
        @endforeach
      </tr>
    </table>    
    @include('questions.multiple_choice.btn_group_radio', ['question'=>$renderable, 'context'=>$context, 'fixedWidth'=>600])
  </div>
@endsection