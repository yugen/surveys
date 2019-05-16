<a href="/surveys/data-dictionary/all/csv" class="btn btn-info btn-xs">Download as csv</a>
@foreach ($surveys as $survey)
    @component('surveys::dictionary.detail', ['survey'=>$survey, 'hide' => true])
    @endcomponent
@endforeach