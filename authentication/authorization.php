<?php
session_start();

if (isset($_COOKIE['LS_ASP']) && !empty($_COOKIE['LS_ASP'])) {
    header('Location: ../home');
    exit();
}

$_SESSION['state'] = bin2hex(random_bytes(32));

$client_id = '1272605975299489812';
$redirect_uri = 'https://meandoyou.me/authentication/callback.php';
$scope = 'identify email guilds';
$state = $_SESSION['state'];

$params = [
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'code',
    'scope' => $scope,
    'state' => $state,
];

header('Location: https://discord.com/api/oauth2/authorize?' . http_build_query($params));
exit();
?>
