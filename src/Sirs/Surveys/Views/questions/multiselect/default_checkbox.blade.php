<div class="checkbox">
	<label>
		<input type="checkbox" name="{{ $question->name }}" id="{{ $question->id }}-input" class="{{ $question->class }}" value="{{ $question->value }}" />
		{{ $question->questionText }}
	</label>
</div>
