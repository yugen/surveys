@extends('app')
@section('content')
<h3>Survey Reports</h3>
<table class="table table-default table-bordered table-striped">
	<thead>
		<th>Survey Name</th>
		<th>Survey Version</th>
		<th>Overview</th>
		<th>Detail View</th>
	</thead>
	<tbody>
		@foreach( $surveys as $survey )
			<tr>
				<td>{{ $survey->name }}</td>
				<td>{{ $survey->version }}</td>
				<td><a href="/surveys/{{$survey->slug}}/report" class="btn btn-default">Go</a></td>
				<td><a href="/surveys/{{$survey->slug}}/report/detail" class="btn btn-default">Go</a></td>
			</tr>
		@endforeach
	</tbody>
</table>
@endsection