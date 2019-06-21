@component('surveys::questions.question', compact('renderable', 'context'))
  @slot('answers')
    @include('surveys::questions.input', ['question'=>$renderable, 'context'=>$context, 'type'=>'text', 'class'=>"sm_datepicker"])
    <script>
      document.addEventListener('DOMContentLoaded', function(){ 
            $('[name="{{$renderable->name}}_field"]').datepicker().on('changeDate', function(evt){
              document.querySelector('[name={{$renderable->name}}]').value = evt.target.value;
            });
      }, false);
    </script>
  @endslot
@endcomponent