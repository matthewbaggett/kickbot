<?php
require_once("../vendor/autoload.php");

use Symfony\Component\Yaml\Yaml;
use Telegram\Bot\Api as TelegramApi;

$config = Yaml::parse(file_get_contents('../config.yml'));
$telegram = new TelegramApi($config['telegram_api_key']);

$app = new \Slim\App();
$app->get('/telegram/webhook/{key}', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $key = $request->getAttribute('key');
    error_log($request->getBody());
    $response->getBody()->write("Hello telegram!");

    return $response;
});

$app->get('/hello/{name}', function( \Slim\Http\Request $request, \Slim\Http\Response $response, $args){
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
})->setName('hello');

$app->get('telegram/setup', function( \Slim\Http\Request $request, \Slim\Http\Response $response, $args){
    global $config, $telegram;
    $telegramCallbackAuth = $telegram->setWebhook("https://kickbot.telegram.thru.io/telegram/webhook/{$config['telegram_api_key']}");
    \Kint::dump($telegramCallbackAuth);
    exit;
});

$app->get('.well-known/acme-challenge/El1K2GOuLyDvjk9-uM8P-Fge_oDMeYHYBL_VVvdvT4s', function( \Slim\Http\Request $request, \Slim\Http\Response $response, $args){
    $response->getBody()->write("El1K2GOuLyDvjk9-uM8P-Fge_oDMeYHYBL_VVvdvT4s.BjQMlU5nwgsexb_DdA7zyb5OWcbghyA_u5G5PCeY610");
    return $response;
});

$app->run();
