<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="création de sites internet, sites vitrines, sites de ventes en ligne, prestashop, woocommerce, wordpress">
    <meta name="description" content="Page d'accueil avec connexion.">
    <title>LUNODOOR - LOGIN</title>

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
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h1 {
            font-size: 4rem;
            color: #e92020;
            text-shadow: 0 0 20px rgba(0, 186, 255, 0.6);
            margin: 0;
            padding: 1rem;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            color: #e92020;
            max-width: 400px;
            width: 100%;
        }

        .login-button {
            display: inline-block;
            padding: 1rem 2rem;
            font-size: 1.5rem;
            color: #fff;
            background-color: #e92020;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #d61e1e;
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
    </style>
</head>
<body>
    <div class="login-container">
        <h1>LUNODOOR EXPLOIT</h1>
        <br>
        <h1>LOGIN</h1>
        <a href="./authentication/authorization.php" class="login-button">Login</a>
    </div>

    <video id="video_background" autoplay loop muted>
        <source src="http://www.eyalkrief.fr/videos/Pac.mp4" type="video/mp4">
        Video not supported
    </video>
</body>
</html>
