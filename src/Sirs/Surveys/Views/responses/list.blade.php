@extends('app');

@section('content')
  <h1>{{ucwords($survey->name)}} Responses</h1>
  <table class="table table-striped table-bordered">
    <tr>
      <th>Respondent</th>
      <th>Started</th>
      <th>Finalized</th>
      <th></th>
    </tr>
    @foreach($responses as $response)
    <tr>
      <td>{{$response->respondent->full_name}} ({{$response->respondent->id}})</td>
      <td>{{$response->started_at or 'not started'}}</td>
      <td>{{$response->finalized_at or 'pending'}}</td>
      <td>
        @if($response->finalized_at)
        <a href="{{route('surveys.{surveySlug}.responses.show', [$survey->slug, $response->id])}}">View response</a>
        @else
        <a href="{{route('surveys.{surveySlug}.responses.show', [$survey->slug, $response->id])}}" class="btn btn-default">Continue survey</a>
        @endif
      </td>
    </tr>
    @endforeach
  </table>
@endsection