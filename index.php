<?php
// Ignore it 
include 'logging.php';

// Slims' Ressources
require 'vendor/autoload.php';

// Kaamelott's lib
require 'lib/lib-kaamelott/kaamelott.class.php';
$kaamelott = new kaamelott;

// Create Your container
$c = new \Slim\Container();

// Override the default Not Found Handler
$c['notFoundHandler'] = function ($c) {
	return function ($request, $response) use ($c) {
		$return['status'] = 0;
		$return['error'] = 'Chemin inconnu';
		return $c['response']
			->withStatus(404)
			->withJson($return);
	};
};

// Declaring the router
$app = new Slim\App($c);

// Home page
$app->get('/', function ($request, $response, $args) {
	return $response->write(file_get_contents('./home.html'));
});

// One random quote without filter
$app->get('/api/random', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->random());
});

// One random quote from one designated season
$app->get('/api/random/livre/{livre}', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->randomLivre($args['livre']));
});

// One random quote from one designated character
$app->get('/api/random/personnage/{personnage}', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->randomPersonnage($args['personnage']));
});

// One random quote from one designated season and character
$app->get('/api/random/livre/{livre}/personnage/{personnage}', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->randomLivrePersonnage($args['livre'], $args['personnage']));
});

// All the quotes without filter
$app->get('/api/all', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->all());
});

// All the quotes from one designated season
$app->get('/api/all/livre/{livre}', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->allLivre($args['livre']));
});

// All the quotes from one designated character
$app->get('/api/all/personnage/{personnage}', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->allPersonnage($args['personnage']));
});

// All the quotes from one designated season and character
$app->get('/api/all/livre/{livre}/personnage/{personnage}', function ($request, $response, $args) {
	global $kaamelott;
	return $response->withJson($kaamelott->allLivrePersonnage($args['livre'], $args['personnage']));
});

// Serve audio files
$app->get('/sounds/{file}', function ($request, $response, $args) {
	return $response->write(file_get_contents('./assets/sounds/'.$args['file'].'.mp3'))->withHeader('Content-type', 'audio/mpeg');
});

$app->run();

?>
