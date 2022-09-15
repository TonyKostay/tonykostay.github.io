<?php

use App\ReviewsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;


require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});
$app->get('/api/feedbacks', [ReviewsController::class, 'getReviews']);
$app->get('/api/feedbacks/{id}', [ReviewsController::class,'getReview']);
$app->get('/api/feedbacks/{id}/delete', [ReviewsController::class,'removeReview'])->add(function (Request $request, RequestHandler $handler) use($app){
    $serverParams = $request->getServerParams();
    $config = require __DIR__ . '/config.php';
    $response = $app->getResponseFactory()->createResponse();
    if (!isset($serverParams ['PHP_AUTH_USER'])){
        return $response->withHeader('WWW-Authenticate', 'Basic realm=Private Area')
            ->withStatus(401, 'Unauthorized');
    }
    if (($serverParams ['PHP_AUTH_USER'] == $config['login'] && ($serverParams ['PHP_AUTH_PW'] == $config['password']))) {
        return $handler->handle($request);
    }

    return $response->withHeader('WWW-Authenticate', 'Basic realm=Private Area')
            ->withStatus(401, 'Unauthorized');

});



$app->post('/api/feedbacks/add', [ReviewsController::class,'addReview']);
$app->get('/home', function (Request $request, Response $response) {
    $response->getBody()->write(file_get_contents(__DIR__ . '/templates/index.html'));
    return $response;
});

$app->run();
