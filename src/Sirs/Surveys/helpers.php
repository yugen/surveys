<?php

if (! function_exists('class_response')) {
	function class_response() {
		return config('surveys.bindings.models.Response', Sirs\Surveys\Models\Response::class);
	}
}

if (! function_exists('class_survey')) {
	function class_survey() {
		return config('surveys.bindings.models.Survey', Sirs\Surveys\Models\Survey::class);
	}
}