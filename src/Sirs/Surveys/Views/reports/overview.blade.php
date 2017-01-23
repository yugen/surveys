@extends('app')
@section('content')
<h3>
	Data Overview for {{ $survey->name }} Survey
	<div class="pull-right">
		<a href="/surveys/data-dictionary#{{ $model->slug }}" class="btn btn-info btn-xs">Data Dictionary</a>
	</div>
</h3>
<p><a href="/surveys/{{ $model->slug }}/report/detail" class="btn btn-default">Show detail view</a></p>
<table class="table table-default table-striped table-bordered" id="{{$survey->name}}_tbl">
	<thead>
		<th>Variable Name</th>
		<th>Question Text</th>
		<th>Data Format</th>
		<th>Total Responses</th>
		<th>Answered Responses</th>
		<th>Unanswered Responses</th>
		<th>Mean</th>
		<th>Median</th>
		<th>Mode</th>
		<th>Min</th>
		<th>Max</th>
		<th>Action</th>
	</thead>
	<tbody>
		@foreach( $survey->getPages() as $page )
			@foreach( $page->getQuestions() as $question )
				<?php $report = $reports[$question->variableName]; ?>
				<tr>
					<td>{{ $question->variableName }}</td>
					<td>{!! $question->questionText !!}</td>
					<td>{{ $question->dataFormat }}</td>
					<td>{{ $report['total'] }}</td>
					<td>{{ $report['answered'] }}</td>
					<td>{{ $report['unanswered'] }}</td>
					@if( $report->has('mean') )
						<td>{{ $report['mean'] }}</td>
					@else
						<td class="text-muted">n/a</td>
					@endif
					@if( $report->has('median') )
						<td>{{ $report['median'] }}</td>
					@else
						<td class="text-muted">n/a</td>
					@endif
					@if( $report->has('mode') )
						<td>{{ $report['mode'] }}</td>
					@else
						<td class="text-muted">n/a</td>
					@endif
					@if( $report->has('range') )
						<td>{{ $report['range']['min'] }}</td>
					@else
						<td class="text-muted">n/a</td>
					@endif
					@if( $report->has('range') )
						<td>{{ $report['range']['max'] }}</td>
					@else
						<td class="text-muted">n/a</td>
					@endif
					<td><a href="/surveys/{{$model->slug}}/report/detail/{{$page->name}}/{{$question->variableName}}" class="btn btn-default">See detailed view</a></td>
				</tr>
			@endforeach
		@endforeach
	</tbody>
</table>
@endsection
@section('scripts')
{{-- <script type="text/javascript">
	$('#{{$survey->name}}_tbl').DataTable({
		"lengthChange": false,
		"paging": false,
		// "searching": false,
		"info": false,
		// "ordering": false,
		});
</script> --}}
@endsection