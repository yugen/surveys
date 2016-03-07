<div>
@foreach ($renderable->contents as $content)
	{!! $content->render() !!}
@endforeach
</div>