<?php
// Getting started

// Bringing in request and response object
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// Loading slim files
require '../vendor/autoload.php';
// Adding DB class
require '../src/config/db.php';

// Initiating slim app
$app = new \Slim\App;

// A sample get method for slim
/*
	-access url without .htaccess 
	'http://localhost/slim_app/public/index.php/hello/Awais'
	-access url with .htaccess which is to be added in public folder
	'http://localhost/slim_app/public/hello/aslam'
	-we add virtual host in apache to make this path even shorter(if needed)
*/
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {

	/*	
		old way
		$name = $request->getAttribute('name');
	*/

	// new way
    // $name = var_dump($args);
    $name = $args['name'];

    // writing to the screen Hello 'name'
    $response->getBody()->write("Hello, $name");

    return $response;
});

// Customer routes
require '../src/routes/customers.php';

// to make it all work this function is being called
$app->run();


?>