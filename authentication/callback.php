<?php
session_start();

$client_id = '1272605975299489812';
$client_secret = 'E_GBCIw8rVi2-4FE-yRBQyCY229Abzdd';
$redirect_uri = 'https://meandoyou.me/authentication/callback.php';
$token_url = 'https://discord.com/api/oauth2/token';
$user_url = 'https://discord.com/api/users/@me';
$user_guilds_url = 'https://discord.com/api/users/@me/guilds';
$required_guild_id = '1267595256556290098'; // The required server ID

function generateToken($length = 100) {
    return bin2hex(random_bytes($length / 2));
}

$default_avatar_urls = [
    'https://better-default-discord.netlify.app/Icons/Ocean-Red.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Orange.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Yellow.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Green.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Indigo.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Blue.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Violet.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Pink.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Black.png',
    'https://better-default-discord.netlify.app/Icons/Ocean-Gray.png'
];

$random_index = array_rand($default_avatar_urls);
$default_avatar_url = $default_avatar_urls[$random_index];

try {
    if (isset($_GET['code']) && isset($_GET['state']) && isset($_SESSION['state']) && $_GET['state'] === $_SESSION['state']) {
        $code = $_GET['code'];
        $data = [
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);

        if ($response === false) {
            header('Location: ../index.php');
            exit;
        }
        curl_close($ch);

        $token = json_decode($response, true);

        if (isset($token['access_token'])) {
            $access_token = $token['access_token'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $user_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
            $user_response = curl_exec($ch);

            if ($user_response === false) {
                header('Location: ../index.php');
                exit;
            }
            curl_close($ch);

            $user = json_decode($user_response, true);

            if (isset($user['id'], $user['username'], $user['email'])) {
                $discord_id = $user['id'];
                $username = $user['username'];
                $email = $user['email'];
                $avatar = isset($user['avatar']) && !empty($user['avatar']) ? 'https://cdn.discordapp.com/avatars/' . $user['id'] . '/' . $user['avatar'] . '.png' : $default_avatar_url;

                if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                    $user_ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
                } else {
                    $user_ip = $_SERVER['REMOTE_ADDR'];
                }

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $user_guilds_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $access_token]);
                $guilds_response = curl_exec($ch);

                if ($guilds_response === false) {
                    header('Location: ../index.php');
                    exit;
                }
                curl_close($ch);

                $guilds_json = json_decode($guilds_response, true);

                // Check if the user is in the required guild
                $in_required_guild = false;
                foreach ($guilds_json as $guild) {
                    if ($guild['id'] === $required_guild_id) {
                        $in_required_guild = true;
                        break;
                    }
                }

                if (!$in_required_guild) {
                    header('Location: ../join');
                    exit;
                }

                $guilds = json_encode($guilds_json);

                $new_token = generateToken();

                $mysqli = new mysqli('localhost', '', '', '');

                if ($mysqli->connect_error) {
                    header('Location: ../home');
                    exit;
                }

                // Prepare SQL query to insert or update user information
                $stmt = $mysqli->prepare('
                    INSERT INTO users (discord_id, username, email, avatar, token, guilds, ip, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, IFNULL(created_at, NOW()), NOW()) 
                    ON DUPLICATE KEY UPDATE 
                        username = VALUES(username), 
                        email = VALUES(email), 
                        avatar = VALUES(avatar), 
                        token = VALUES(token), 
                        guilds = VALUES(guilds), 
                        ip = VALUES(ip), 
                        updated_at = NOW()
                ');
                if ($stmt === false) {
                    header('Location: ../index.php');
                    exit;
                }

                $bind = $stmt->bind_param('sssssss', $discord_id, $username, $email, $avatar, $new_token, $guilds, $user_ip);
                if ($bind === false) {
                    header('Location: ../index.php');
                    exit;
                }

                $execute = $stmt->execute();
                if ($execute === false) {
                    header('Location: ../index.php');
                    exit;
                }

                $stmt->close();
                $mysqli->close();

                setcookie('LS_ASP', $new_token, time() + 3600, '/', '', false, true);
                header('Location: ../home');
                exit;
            } else {
                header('Location: ../index.php');
                exit;
            }
        } else {
            header('Location: ../index.php');
            exit;
        }
    } else {
        header('Location: ../index.php');
        exit;
    }
} catch (Exception $e) {
    echo 'Exception caught: ' . $e->getMessage();
}
?>
