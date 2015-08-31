<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helper.php';

$app = new Silex\Application();

$app['title']    = $config['title'];
$app['base_url'] = $config['base_url'];

$app['repositories'] = $config['repositories'];

$app['cache_dir'] = __DIR__ . '/' . $config['cache_dir'] ?: 'web/proxy';

$app['browser'] = $app->share(function () {
	$client = new Buzz\Client\Curl();
	$client->setTimeout(30);
	return new Buzz\Browser($client);
});

$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
	'http_cache.cache_dir' => __DIR__ . '/cache/',
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/views',
));

require_once __DIR__ . '/routes.php';

return $app;
