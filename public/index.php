<?php
require_once("../vendor/autoload.php");

use Symfony\Component\Yaml\Yaml;
use Telegram\Bot\Api as TelegramApi;

$config = Yaml::parse(file_get_contents('../config.yml'));
$telegram = new TelegramApi($config['telegram_api_key']);
$response = $telegram->setWebhook("https://kickbot.telegram.thru.io/telegram/webhook/{config['telegram_api_key']");

$app = new \Slim\App();
$app->get('/telegram/webhook/{key}', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $key = $request->getAttribute('key');
    error_log($request->getBody());
    $response->getBody()->write("Hello telegram!");

    return $response;
})->setName('hello');


$app->get('/hello/{name}', function ($request, $response, $args) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
})->setName('hello');

$app->run();
