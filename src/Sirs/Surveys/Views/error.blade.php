@if ( isset($context['errors']) && $context['errors']->has( $question->name ) )
  <div class="error-block alert alert-danger" data-for-question="{{$question->name}}">
		<ul class="error-list list-unstyled m-0">
		@foreach( $context['errors']->get( $question->name ) as $error )
			<li>{{$error}}</li>
		@endforeach
		</ul>
  </div>
@endif
