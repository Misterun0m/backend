<?php

require __DIR__ . '/../vendor/autoload.php';

$client = new Google\Client();
$client->setApplicationName('Enviar correos');
$client->setScopes(Google\Service\Gmail::GMAIL_SEND);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');

$authUrl = $client->createAuthUrl();

echo "Abre este enlace en tu navegador:\n\n";
echo $authUrl;

?>