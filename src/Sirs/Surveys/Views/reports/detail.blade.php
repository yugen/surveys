@extends('app')
@section('content')
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">
				{{ $survey->name }} Response Report
				<div class="pull-right">
					<a href="/surveys/{{$model->slug}}/report" class="btn btn-xs btn-default">Back to Overview</a>
				</div>
			</h3>
		</div>
		<div class="panel-body">
			<ul class="nav nav-tabs" role="tablist">
				<?php 
				if( !is_null($pageName) ){
					$navTabCounter = 1;
				}else{
					$navTabCounter = 0; 	
				}
				?>
				@foreach( $survey->getPages() as $page  )
					@if( count($page->getQuestions()) > 0 )
						<li role="presentation" @if( $navTabCounter == 0 || $pageName == $page->name ) class="active" @endif>
						<a data-target="#{{ $page->name }}-tab" aria-controls="{{ $page->name }}" role="tab" data-toggle="tab">{{ $page->title }} </a></li>
						<?php $navTabCounter += 1 ?>
					@endif
				@endforeach
		    </ul>
		    <div class="tab-content">
		    	<?php 
		    		$navTabContentCounter = (!is_null($pageName)) ? 1 : 0;
				?>
		        @foreach( $survey->getPages() as $page )
		        	@if( count($page->getQuestions()) > 0 )
			        <div role="tabpanel" class="tab-pane @if( $navTabContentCounter == 0 || $pageName == $page->name ) active @endif" id="{{ $page->name }}-tab">
			        	<div class="row">
				        	<div class="col-xs-3">
								<ul class="nav nav-pills nav-stacked" role="tablist" id="{{$page->name}}_pills">
									<?php 
									if( !is_null($variableName) ){
										$pillCounter = 1;
									}else{
										$pillCounter = 0; 	
									}
									 ?>
									@foreach( $page->getQuestions() as $question )
										<li role="presentation" @if ( $pillCounter == 0 || $variableName == $question->variableName) class="active" @endif >
										<a data-target="#{{$question->variableName}}" aria-controls="{{$question->variableName}}" role="tab" data-toggle="tab">{{$question->variableName}}</a></li>
										<?php $pillCounter +=1 ?>
									@endforeach
							    </ul>
							</div>
							<div class="col-xs-9">
								<div class="tab-content" id="tab-content">
									<?php 
									if( !is_null($variableName) ){
										$pillTabContentCounter = 1;
									}else{
										$pillTabContentCounter = 0; 	
									}
									 ?>
									@foreach( $page->getQuestions() as $question)
										<?php $report = $reports[$question->variableName]; ?>
										<div role="tabpanel" class="tab-pane @if ($pillTabContentCounter == 0 || $variableName == $question->variableName ) active @endif" id="{{$question->variableName}}">

											<h4>Question Metadata</h4>
											<dl class="dl-horizontal"">
												<dt style="margin-left:0; text-align:left;" >Variable Name</dt>
												<dd>{{ $question->variableName }}</dd>
												<dt style="margin-left:0; text-align:left;">Data Format</dt>
												<dd>{{ $question->dataFormat }}</dd>
												<dt style="margin-left:0; text-align:left;">Question Text</dt>
												<dd >{{ $question->questionText }}</dd>
											</dl>
											<hr />
											<h4>Answer Frequency</h4>
											<table class="table table-default table-bordered">
												<tr>
													<th>Answered Responses</th>
													<th>Unanswered Responses</th>
													<th>Total Responses</th>
												</tr>
												<tr>
													<td>{{ $report['answered'] }}</td>
													<td>{{ $report['unanswered'] }}</td>
													<td>{{ $report['total'] }}</td>
												</tr>
											</table>
											<hr />
											@if(
												$report->has('mean') ||
												$report->has('median') ||
												$report->has('mode') ||
												$report->has('range')
											)
												<h4>Statistics</h4>
												<table class="table table-default table-bordered">
													<thead>
														@if( $report->has('mean') ) <th>Mean</th> @endif
														@if( $report->has('median') ) <th>Median</th> @endif
														@if( $report->has('mode') ) <th>Mode</th> @endif
														@if( $report->has('range') ) <th>Min</th> @endif
														@if( $report->has('range') ) <th>Max</th> @endif
													<thead>
													<tbody>
														@if( $report->has('mean') ) <td>{{$report['mean']}}</td> @endif
														@if( $report->has('median') )
															<td>
																{{ ($report->has('options')) 
																		? $report['options'][$report['median']]['label'] 
																		: $report['median'] }}
															</td> 
														@endif
														
														@if( $report->has('mode') ) 
															@if( $report->has('options') )
																<td>{{ $report['options'][$report['mode']]['label']  }}</td>
															@else{
																<td>{{$report['mode']}}</td>
															}
															 @endif
														@endif
														@if( $report->has('range') ) <td>{{$report["range"]['min']}}</td> @endif
														@if( $report->has('range') ) <td>{{$report["range"]['max']}}</th> @endif
													</tbody>
												</table>
												<hr />
											@endif
											@if( $report->has('options') )
												<h4>Options</h4>
												<div id="{{$question->variableName}}_chart">
													  <svg style="min-width:500px; min-height:150px;"></svg>
												</div>
												<table id="{{ $question->variableName }}_options" class="table table-default table-bordered table-striped">
													<thead>
														<th>Value</th>
														<th>Label</th>
														<th>Frequency</th>
													</thead>
													<tbody>
														@foreach( $question->options as $option )
															<tr>
																<td>{{ $option->value }}</td>
																<td>{{ $option->label }}</td>
																<td>{{ $report['options'][$option->value]['count'] }}
															</tr>
														@endforeach
													</tbody>
												</table>
												<hr />
												
												<script type="text/javascript">
													<?php $vFormatted = str_replace( '_', '', $question->variableName ); ?> 
													var width = document.getElementById("tab-content").offsetWidth;
													
													var {{$vFormatted}}data =  [{key: '{{$vFormatted}}', values:[<?php $commaCounter = count($question->options) - 1; ?>@foreach($question->options as $option ){'label':'{{$option->value}}','value': {{ $report['options'][$option->value]['count'] }}}@if( $commaCounter > 0),@endif<?php $commaCounter -= 1; ?>@endforeach ]}];
													
													nv.addGraph(function() {
													  var chart = nv.models.discreteBarChart()
													    .x(function(d) { return d.label })
													    .y(function(d) { return d.value })
													    // .staggerLabels(true)
													    // .tooltips(false)
													    .showValues(true)
													    .height(150)
													    .width(width)
													    ''
													  d3.select('#{{$question->variableName}}_chart svg')
													    .datum({{$vFormatted}}data)
													    .transition().duration(500)
													    .call(chart)
													    ;
													  nv.utils.windowResize(chart.update);
													  return chart;
													});
												</script>
											@endif
									    </div>
									    <?php $pillTabContentCounter += 1 ?>
								    @endforeach
								</div>
							</div>
						</div>
			        </div>
			    	<?php $navTabContentCounter += 1; ?>
			    @endif
		        @endforeach
		    </div>
		</div>
	</div>
@endsection