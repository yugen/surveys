@extends('questions.question')

@section('answers')
  @include('questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'text', 'class'=>"sm_datepicker"])
  <script>
 	document.addEventListener('DOMContentLoaded', function(){ 
        $('[name="{{$renderable->name}}_field"]').datepicker().on('changeDate', function(evt){
          document.querySelector('[name={{$renderable->name}}]').value = evt.target.value;
        });

   }, false);
</script>
@endsection