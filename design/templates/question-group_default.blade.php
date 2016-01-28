
<div id="{{ $questiongroup->id }}" class="{{ $questiongroup->class }}">
	@foreach ($questiongroup->questions as $question)
		{{ $question->render() }}
	@endforeach
</div>