
<div id="{{ $questiongroup->id }}" class="form-group {{ $questiongroup->class }}">
	<label>Name:</label>
		<div class="form-inline">
			@foreach ($questiongroup->questions as $question)
				{{ $question->render() }}
			@endforeach
		</div>
	
</div>