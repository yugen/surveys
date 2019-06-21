<script>
    // Plugins
    $(document).ready(function(){
        $('[data-skipTarget]').skipTrigger();
        $('.mutually-exclusive').mutuallyExclusive();
        $('.sm_datepicker').datepicker({
            format: "mm/dd/yyyy"
        });

        $('.timepicker').timepicker({
            minTime: '5:00am',
            maxTime: '7:00pm'
        }).on('keydown', function(evt){
            if([13,38,40].indexOf(evt.keyCode) < 0) {
            $(this).timepicker('hide');
            }
        }).on('changeTime', function(){
            $(this).removeClass('input-error');
        }).on('timeFormatError', function(){
            $(this).addClass('input-error');
        });
    });
</script>