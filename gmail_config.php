<?php

require __DIR__ . '/vendor/autoload.php';

function getGoogleClient(){

    $client = new Google\Client();

    $client->setAuthConfig(__DIR__ . '/credentials.json'); 
    $client->addScope(Google\Service\Gmail::GMAIL_SEND);

    return $client;
}
