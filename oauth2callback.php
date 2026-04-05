<?php
require __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('credentials.json');

$client->addScope(Google_Service_Gmail::GMAIL_SEND);

$client->setRedirectUri('http://localhost/backend/oauth2callback.php');

/* ESTO ES LO IMPORTANTE */
$client->setAccessType('offline');   // permite refresh_token
$client->setPrompt('consent');       // fuerza a Google a enviarlo

if (!isset($_GET['code'])) {

    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();

} else {

    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    file_put_contents('token.json', json_encode($accessToken));

    echo "✅ Token generado correctamente. Ya puedes enviar correos.";
}
