<?php

$app->get('/', function () use ($app) {
	$body = $app['twig']->render('index.html.twig', array(
		'app' => $app,
	));

	return new Symfony\Component\HttpFoundation\Response($body, 200, array('Cache-Control' => 's-maxage=3600,public'));
});

$app->get('{rep}/packages.json', function ($rep) use ($app) {
	checkConfig($app, $rep);

	$url = $app['repositories'][$rep] . "/packages.json";
	$response = $app['browser']->get($url);
	if (!$response->isOk()) {
		$app->abort($response->getStatusCode(), "");
	}

	$responseJson = json_decode($response->getContent(), true);

	// convert
	if (isset($responseJson['notify']) && $responseJson['notify'][0] === '/') {
		$responseJson['notify'] = $app['repositories'][$rep] . $responseJson['notify'];
	}
	if (isset($responseJson['notify-batch']) && $responseJson['notify-batch'][0] === '/') {
		$responseJson['notify-batch'] = $app['repositories'][$rep] . $responseJson['notify-batch'];
	}
	if (isset($responseJson['search']) && $responseJson['search'][0] === '/') {
		$responseJson['search'] = $app['repositories'][$rep] . $responseJson['search'];
	}

	$responseJson['providers-url'] = "/" . $rep . "/p/%package%$%hash%.json";

	$path = '';
	$file = 'packages.json';

	$localPath = $app['cache_dir'] . "/" . $rep . $path . $file;
	makeDirIfNeeded($localPath);
	makeLocalCache($responseJson, $localPath);

	return $app->json($responseJson);
});

$app->get('{rep}/p/{provider}${hash}.json', function ($rep, $provider, $hash) use ($app) {
	checkConfig($app, $rep);

	$path = "/p/";
	$file = $provider . "$" . $hash . ".json";

	$url = $app['repositories'][$rep] . $path . $file;
	$localPath = $app['cache_dir'] . "/" . $rep . $path . $file;

	return load($app, $url, $localPath);
});

$app->get('{rep}/p/{namespace}/{package}${hash}.json', function ($rep, $namespace, $package, $hash) use ($app) {
	checkConfig($app, $rep);

	$path = "/p/" . $namespace . "/";
	$file = $package . "$" . $hash . ".json";

	$url = $app['repositories'][$rep] . $path . $file;
	$localPath = $app['cache_dir'] . "/" . $rep . $path . $file;

	return load($app, $url, $localPath);
});

$app->get('{rep}/p/{namespace}/{package}.json', function ($rep, $namespace, $package) use ($app) {
	checkConfig($app, $rep);

	$path = "/p/" . $namespace . "/";
	$file = $package . ".json";

	$url = $app['repositories'][$rep] . $path . $file;
	$localPath = $app['cache_dir'] . "/" . $rep . $path . $file;

	return load($app, $url, $localPath);
});
