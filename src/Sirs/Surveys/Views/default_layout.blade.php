<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>App</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker3.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.css">
        <style>
            .question-block{
                position: relative;
                margin: 0 0 0;
                border-top: solid 1px #eee;
                padding: .75em 0;
            }

            .question-block.sub-question{
                margin-top: 0;
                padding-left: 1em;
                border-top: none;
            }

            label{
                font-weight: normal;
            }

            .question-answers{
                margin: .5em auto
            }

            .error-block{
                {{-- @extend .alert;
                @extend .alert-danger; --}}
                font-size: 12px;
                padding: .5em
            }
            .error-block>.error-list{
            }

            .question-block.vertical .btn-group{
                {{-- @extend .btn-group-vertical; --}}
            }

            .likert-container .question-text
            {
                width: 475px;
            }

            #page-nav.affix {
                top: 10px;
            }

            .nav li.active {
                border-bottom: 3px solid #f0f;
            }

            .list-no-indent{
                padding-left: 1em;
            }

            .separator-bottom{
                margin-bottom: 1em;
                border-bottom: solid 1px $gray-lighter;
                padding-bottom: 1em;
            }

            .finalized-response-warning{
                @extend .alert;
                @extend .alert-warning;
                padding: .7em;
                margin: 0;
                margin-right: 1em;
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
    </head>
    <body>
        <div class="container">
            @yield('content')
        </div>

        {{-- Skip trigger --}}
        <script>
            ( function( $ ){
            'use strict';
            var alreadyProcessed = function(component){
                var processed = false;
                if ($(component).attr("type") == 'radio'){
                $('input:radio[name='+$(component).attr('name')+']').each(function(){
                    if($(this).hasClass('skipTrigger_processed')){
                    processed = true;
                    return false;
                    }
                });
                }else{
                if($(component).hasClass('skipTrigger_processed')){
                    processed = true;
                }
                }
                return processed;
            };
            var defaults = {
            };
            var methods = {
                init: function( aOptions ){

                var settings = $.extend ( {}, defaults, aOptions );
                return $(this).each( function(){
                    if (alreadyProcessed(this)){
                    return;
                    }

                    var $this = $( this ), //the widget (also the trigger)
                    data = $this.data('widget'),
                    target = $('#'+$this.attr('data-skipTarget')), //element with skip pattern fields
                    targets = $this.skipTrigger('getTargets'), //element with skip pattern fields
                    triggeringValue = $this.attr('value'); //value that exposes skip pattern fields
                    var triggeringValues = $this.skipTrigger('getTriggeringValues');

                    $this.addClass('skipTrigger_processed');

                    // Bind the change handler
                    $this.skipTrigger('bindChangeEvent');

                    // set up the widget's data
                    if ( !data ) {
                    $this.data('settings', settings);
                    $this.data('target', target);
                    $this.data('targets', targets);
                    $this.data('triggeringValue', triggeringValue);
                    $this.data('triggeringValues', triggeringValues);
                    }

                    // Toggle based on data
                    $this.skipTrigger('toggleTarget');

                });
                },
                getTargets: function(){
                var $this = this;
                var targets = {};

                if($this.skipTrigger('getTriggerFormType') == 'radio'){
                    $('input:radio[name='+$this.attr('name')+']').each(function(){
                    var targetId = $(this).attr('data-skipTarget');
                    if (typeof targetId != 'undefined' && targetId) {
                        if (typeof targets[targetId] == 'undefined') {
                        targets[targetId] = [];
                        }

                        targets[targetId].push($(this).val());
                    }
                    });
                }else if($this.skipTrigger('getTriggerFormType') == 'option'){
                    if ($this.parent('select').hasClass('skipTrigger_processed')) {
                    return;
                    }
                    $this.parent('select').find('option').each(function(){
                    var targetId = $(this).attr('data-skipTarget');
                    if (typeof targetId != 'undefined' && targetId) {
                        if (typeof targets[targetId] == 'undefined') {
                        targets[targetId] = [];
                        }
                        targets[targetId].push($(this).val());
                    }
                    });
                    $this.parent('select').addClass('skipTrigger_processed');
                }else{
                    var targetId = $(this).attr('data-skipTarget');
                    targets[targetId] = new Array($(this).val());
                }
                // console.log(targets);
                return targets;
                },
                getTriggeringValues: function(){
                var $this = $(this);
                var values = [];
                var formType = $this.skipTrigger('getTriggerFormType');
                if( formType == 'radio'){
                    $('input:radio[name='+$this.attr('name')+']').each(function(){
                    var trigger = $(this).attr('data-skipTarget');
                    if (typeof trigger != 'undefined' && trigger) {
                        values.push($(this).attr('value'));
                    }
                    });
                }else if( formType == "option" ){
                    $this.parent().find('option').each(function(){
                    var trigger = $(this).attr('data-skipTarget');
                    if (typeof trigger != 'undefined' && trigger) {
                        values.push($(this).attr('value'));
                    }
                    });
                }else{
                    values.push($this.attr('value'));
                }
                return values;
                },
                bindChangeEvent: function(){
                var $this = $(this);
                if ($this.skipTrigger('getTriggerFormType') == 'radio'){
                    $('input:radio[name='+$this.attr('name')+']').on('change', function(evt){
                    $this.skipTrigger('toggleTarget');
                    });
                }else if($this.skipTrigger('getTriggerFormType') == 'option'){
                    $this.parent('select').on('change', function(evt){
                    $this.skipTrigger('toggleTarget');
                    });
                }else{
                    $this.on('change', function(evt){
                    $this.skipTrigger('toggleTarget');
                    });
                }      
                },
                toggleTarget: function(){
                var $this = this, //the widget (also the trigger)
                    data = $this.data();

                if ($this.skipTrigger('getTriggerFormType') == 'checkbox') {
                    if ($this.prop('checked')){
                    data.target.fadeIn('fast', function(){
                        $(this).trigger('show');
                    });
                    }else{
                    data.target.fadeOut('fast', function(){
                        $(this).trigger('hide');
                    });
                    data.target.find('input[type=text]').val('');
                    data.target.find('input[type=radio],input[type=checkbox]').prop('checked', false);
                    }  
                }else{
                    var triggerFieldVal = $this.skipTrigger('getTriggerFieldValue');
                    // foreach of the targets 
                    // check to see if the triggerFieldVal is in the values array
                    var formType = $this.skipTrigger('getTriggerFormType');
                    for(var id in data.targets){
                    if (formType == 'radio' || formType == 'option'){
                        if(data.targets[id].indexOf(triggerFieldVal) > -1) {
                        $('#'+id).fadeIn('fast', function(){
                            $(this).trigger('show');
                        });
                        }else{
                        $('#'+id).fadeOut('fast', function(){
                            $(this).trigger('hide');
                        });

                        $('#'+id).find('input, textarea, select').not('input[type=radio],input[type=checkbox]').each(function(){
                            $(this).val('').trigger('change');
                        });
                        
                        $('#'+id).find('input[type=radio],input[type=checkbox]').each(function(){
                            $(this).prop('checked', false).trigger('change');
                        });
                        }
                    }else{ 
                        if(typeof triggerFieldVal != 'undefined' && triggerFieldVal !== ''){
                        $('#'+id).fadeIn('fast', function(){
                            $(this).trigger('show');
                        });
                        }else{
                        $('#'+id).fadeOut('fast', function(){
                            $(this).trigger('hide');
                        });
                        $('#'+id).find('input, textarea, select').not('input[type=radio],input[type=checkbox]').each(function(){
                            $(this).val('').trigger('change');
                        });
                        $('#'+id).find('input[type=radio],input[type=checkbox]').each(function(){
                            $(this).prop('checked', false).trigger('change');
                        });
                        }
                    }
                    }
                }
                },
                getTriggerFormType: function(){
                var $this = $(this),
                    tagName = $this.prop('tagName').toLowerCase();
                switch(tagName){
                    case 'option':
                    return 'option';
                    case 'select':
                    return 'select';
                    default:
                    var inputType = $this.attr('type').toLowerCase();
                    switch(inputType){
                        case 'date':
                        case 'search':
                        case 'color':
                        case 'date':
                        case 'datetime':
                        case 'datetime-local':
                        case 'email':
                        case 'month':
                        case 'number':
                        case 'range':
                        case 'search':
                        case 'tel':
                        case 'time':
                        case 'url':
                        case 'week':
                        return 'text';
                        default:
                        return inputType;
                    }
                }
                },
                getTriggerFieldValue: function(){
                var $this = $(this),
                    $el = $this,
                    formFieldType = $this.skipTrigger('getTriggerFormType');

                switch(formFieldType){
                    case 'radio':
                    return $('input:radio[name='+$el.attr('name')+']:checked').val();
                    case 'option':
                    $el = $this.parent('select');
                    default:
                    return $el.val();
                }
                },
            };

            $.fn.skipTrigger = function( method ) {
                if ( methods[method] ) {
                return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
                }else if ( typeof method === 'object' || ! method) {
                return methods.init.apply(this, arguments);
                }else{
                    $.error( 'Method '+method+' does not exist on jQuery.skipTrigger' );
                }
            };
            } ( jQuery ) );        
        </script>

        {{-- mutually exclusive --}}
        <script>
            ( function( $ ){
            var defaults = {
                onClearExclusive: function(exclusive){},
                onClearOthers: function(others){}
            };
            var methods = {
                init: function( aOptions ){
                var settings = $.extend ( {}, defaults, aOptions );
                return $(this).each( function(){
                    console.log('mutuallyExclusive.init()');
                    var $this = $( this ),
                    data = $this.data('widget'),
                    exclusiveInput = $this.find('input.exclusive'),
                    otherInputs = $this.find('input, select, textarea').not('.exclusive');

                    $this.settings = settings;

                    exclusiveInput.bind('change', function(evt){
                    $this.mutuallyExclusive('clearOthers', $(this));
                    });
                    otherInputs.bind('change', function(evt){
                    $this.mutuallyExclusive('clearExclusive');
                    });

                    if ( !data ) {
                    $this.data('settings', settings);
                    $this.data('exclusiveInput', exclusiveInput);
                    $this.data('otherInputs', otherInputs);
                    }
                });
                },
                clearExclusive: function(){
                var $this = $(this),
                    exclusiveInput = $this.data('exclusiveInput');
                    otherInputs = $this.data('otherInputs');
                otherInputs.each(function(){
                    if( $(this).prop('checked') || $(this).val() ){
                    exclusiveInput.prop('checked', false);
                    }
                });
                this.settings.onClearExclusive(exclusiveInput);
                $this.trigger('mutuallyExclusive:changed', [$this.data('exclusiveInput'), $this.data('otherInputs')]);
                $this.trigger('mutuallyExclusive:clearedExclusive', [exclusiveInput, otherInputs]);
                },
                clearOthers: function(exclusiveChanged){
                var $this = $(this); //referrs to plugin
                if( exclusiveChanged.prop('checked') ){
                    var others = $this.find('input, select, textarea').not(exclusiveChanged);
                    others.each(function(){
                        if( $(this).prop('tagName') == 'INPUT' && ( $(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio' ) ){
                        $(this).prop('checked', false);
                        }else{
                        $(this).val('');
                        }
                    });
                    this.settings.onClearOthers(others);
                    $this.trigger('mutuallyExclusive:changed', [$this.data('exclusiveInput'), $this.data('otherInputs')]);
                    $this.trigger('mutuallyExclusive:clearedOthers', [$this.data('exclusiveInput'), $this.data('otherInputs')]);
                }
                }
            };

            $.fn.mutuallyExclusive = function( method ) {
                if ( methods[method] ) {
                    return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
                }else if ( typeof method === 'object' || ! method) {
                    return methods.init.apply(this, arguments);
                }else{
                    $.error( 'Method '+method+' does not exist on jQuery.mutuallyExclusive' );
                }
            };
            } ( jQuery ) );
        </script>

        @stack('js')
    </body>
</html>