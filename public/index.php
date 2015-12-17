<?php
require_once("../vendor/autoload.php");

use Symfony\Component\Yaml\Yaml;

$config = Yaml::parse(file_get_contents('../config.yml'));

\Kint::dump($config);

$app = new \Slim\App();
$app->get('/hello/{name}', function ($request, $response, $args) {
    $url = $this->router->pathFor('hello', ['name' => 'Josh']);

    return $response;
})->setName('hello');