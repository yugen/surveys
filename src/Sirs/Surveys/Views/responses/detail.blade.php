@extends('app')

@section('content')
<h1>{{ucwords($response->survey->name)}} response for {{$response->respondent->full_name}}</h1>
<div class="alert alert-info">
  This survey response has been finalized.
</div>
<table class="table table-striped table-bordered">
  <tr>
    <th>Variable</th>
    <th>Value</th>
  </tr>
  @foreach($response->getDataAttributes() as $key => $val)
  <tr>
    <td>{{$key}}</td>
    <td class="{{($val === null) ? 'text-muted': ''}}">{{$val or 'null'}}</td>
  </tr>
  @endforeach
</table>
@endsection