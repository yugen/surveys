<script>
  @if(config('surveys.autosave.enabled'))
  // autosave
  $(document).ready(function(){

    var activeRequest = false;

    var formIsDirty = false;
    $('form.sirs-survey').on('change', function(){ formIsDirty = true; });

    var notify = function(){
      $('#notification-time').text(moment().format('hh:mm:ss'))
      $('#flast-notification').fadeIn();
      setTimeout( function(){$('#flast-notification').fadeOut()}, {{config('surveys.autosave.notify_time', 2500) }});
    }

    var autosave = function(){
      if(!activeRequest && formIsDirty){
        activeRequest = true;
        $.ajax({
          url: '{{route('surveys.autosave', [ get_class($context['respondent']),  $context['respondent']->id,  $context['survey']['object']->slug,  $context['response']->id ])}}',
          method: 'PUT',
          data: $('form.sirs-survey').serializeArray(),
          success: function(){
            activeRequest = formIsDirty = false;
            @if(config('surveys.autosave.notify'))
            notify();
            @endif
          },
          error: function(xhr, error){
            activeRequest = formIsDirty = false;
          }
        })    
      }
    };

    setInterval(autosave, {{config('surveys.autosave.frequency', 10000)}});
  });
  @endif
</script>
