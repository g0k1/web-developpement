
<?php
if (isset($_COOKIE['LS_ASP']) && !empty($_COOKIE['LS_ASP'])) {
    $token = $_COOKIE['LS_ASP'];
    
	include("../componements/php/database_conn.php");

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT guilds FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($guilds);
    $stmt->fetch();
    $stmt->close();
    
    $guilds_array = json_decode($guilds, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $guilds_array = explode(',', $guilds);
    }

    if (in_array("1245354780507770962", $guilds_array)) {
        header("Location: ../hub");
        exit();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="création de sites internet, sites vitrines, sites de ventes en ligne, prestashop, woocommerce, wordpress">
    <meta name="description" content="La page qui présente Eyal Krief, concepteur-développeur spécialisé dans la création de sites internet, vitrines, et de sites de ventes en ligne sur Prestashop, Woocommerce.">
    <title>LUNODOOR - EXPLOIT PANEL</title>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=VT323&display=swap">

    <!-- CSS -->
    <style>
        /* Global styles */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'VT323', cursive;
        }

        body {
            background: #000 radial-gradient(#620d0d, #000000);
            color: #e92020;
            font-size: 1.25rem;
        }

        h1 {
            font-size: 4rem;
            text-align: center;
            margin: 1rem 0;
            color: #e92020;
            text-shadow: 0 0 20px rgba(0, 186, 255, 0.6);
        }

        nav {
            background-color: #181818;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
            margin: 2rem auto;
            width: 90%;
            max-width: 1200px;
            padding: 1rem;
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .navbar a {
            color: #bd2424;
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 10px;
            font-size: 1.5rem;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar a:hover {
          background-color: #6000000;
          color: #eb2626;
        }

        .navbar .active {
            background-color: #6000000;
            color: #eb2626;
            font-weight: bold;
        }

        .navbar .active:hover {
            background-color: #6000000;
            color: #eb2626;
        }

        /* Video background */
        #video_background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        /* Frosted effect */
        .frosted-effect {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .frosted-glass {
    background: rgb(0 0 0 / 35%);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 0 20px rgb(0 0 0 / 30%);
    backdrop-filter: blur(10px);
    color: #e92020;
    max-width: 800px;
    margin: 0 auto;
}
    </style>
</head>
<body>
    <h1>LUNODOOR - EXPLOIT PANEL</h1>
    <div class="frosted-effect">
        <div class="frosted-glass">
            <!-- Content inside the frosted glass -->
            <p>PANEL PRIVER !</p>
        </div>
    </div>

    <video id="video_background" autoplay loop muted>
        <source src="http://www.eyalkrief.fr/videos/Pac.mp4" type="video/mp4">
        Video not supported
    </video>
</body>
</html>
