@extends(config('surveys.chromeTemplate'))

@section('content')
<div id="survey-list-container">
    <h2>Survey Data Dictionary</h2>
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked">
                @foreach($surveys as $survey)
                    <li>
                        <a href="#{{$survey->slug}}" class="dictionary-link" id="{{$survey->slug}}-link">{{$survey->name}}</a>
                    </li>
                @endforeach
            </ul>
        </div> 
        <div class="col-md-9" id="dictionary-detail" style="border-left: 1px solid #eee">
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('.dictionary-link').on('click', function(evt){
            $(this).parent().addClass('active').siblings().removeClass('active');
            $.get('/surveys/data-dictionary/'+$(this).attr('href').substr(1)).done(function(data, status){
                $('#dictionary-detail').html(data);
            });
        });
        var hash = (window.location.hash) ? window.location.hash : $('.dictionary-link').first().attr('href');
        $('[href="'+hash+'"]').trigger('click');
    })
</script>
@endpush