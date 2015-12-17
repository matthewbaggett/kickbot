<?php
require_once("../vendor/autoload.php");

use Symfony\Component\Yaml\Yaml;
use Telegram\Bot\Api as TelegramApi;

$config = Yaml::parse(file_get_contents('../config.yml'));
$telegram = new TelegramApi($config['telegram_api_key']);
$response = $telegram->setWebhook('http://kickbot.telegram.thru.io');


$app = new \Slim\App();
$app->get('/hello/{name}', function ($request, $response, $args) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
})->setName('hello');

$app->run();
