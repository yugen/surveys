<h3>
    {{$survey->document->title}}
    <small class="text-muted">(table: {{$survey->response_table}})</small>
    <div class="pull-right">
        <a href="/surveys/{{$survey->slug}}/report/" class="btn btn-info btn-xs">Data Summary</a>
    </div>
</h3>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 10%">Name</th>
            <th style="width: 45%">Question Text</th>
            <th style="width: 40%">Options</th>
            <th style="width: 5%">Category</th>
            {{-- <th style="width: 5%">DB Type</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($survey->surveyDocument->getQuestions() as $q)
            @foreach($q->variables as $var)
                <tr>
                    <td>{{$var->name}}</td>
                    <td>{!!$q->questionText!!}</td>
                    <td>
                        @if($q->hasOptions())
                            <ul class="list-unstyled">
                            @foreach($q->options as $option)
                                <li><strong>{{$option->value}}</strong>: {{$option->label}}</li>
                            @endforeach
                            </ul>
                        @else
                          <span class="text-muted">n/a</span>
                        @endif
                    </td>
                    <td>{{$var->type}}</td>
                    {{-- <td>{{$var->dataFormat}}</td> --}}
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>