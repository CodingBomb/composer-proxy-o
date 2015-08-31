<?php

if (!function_exists('checkConfig')) {
	function checkConfig($app, $rep) {
		if (!isset($app['repositories'][$rep])) {
			$app->abort(404, "Not Found!");
		}
		return true;
	}
}

if (!function_exists('load')) {
	function load($app, $url, $localPath) {
		$cache = checkLocalCache($localPath);
		if (false === $cache) {
			$response = loadFromRepository($app, $url);
			makeLocalCache($response, $localPath);
			return $response;
		}
		return $cache;
	}
}

if (!function_exists('checkLocalCache')) {
	function checkLocalCache($localPath) {
		makeDirIfNeeded($localPath);
		if (file_exists($localPath)) {
			return file_get_contents($localPath);
		}
		return false;
	}
}

if (!function_exists('makeLocalCache')) {
	function makeLocalCache($content, $filePath) {
		file_put_contents($filePath, $content);
	}
}

if (!function_exists('loadFromRepository')) {
	function loadFromRepository($app, $url) {
		$response = $app['browser']->get($url);
		if (!$response->isOk()) {
			$app->abort($response->getStatusCode(), "Response Error!");
		}

		$responseContent = $response->getContent();

		return $responseContent;
	}
}

if (!function_exists('makeDirIfNeeded')) {
	function makeDirIfNeeded($filePath) {
		$dir = dirname($filePath);
		if (!is_dir($dir)) {
			@mkdir($dir, 0777, true);
		}
	}
}
