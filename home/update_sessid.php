<?php
include("../componements/php/database_conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sessid = $_POST['sessid'];
    $token = $_POST['token'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET sessid_cookie = ? WHERE token = ?");
    $stmt->bind_param("ss", $sessid, $token);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
?>
