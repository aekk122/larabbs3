<?php

function route_class() {
	return str_replace('.', '-', Route::currentRouteName());
}

function make_excerpt($body, $length = 20) {
	$excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($body)));
	return str_limit($excerpt, $length);
}