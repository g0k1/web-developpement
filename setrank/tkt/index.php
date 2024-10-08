<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #121212;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 320px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input, select {
            width: 93%;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #333;
            color: #ffffff;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .notification {
            margin-top: 20px;
            padding: 15px;
            color: white;
            background-color: #4CAF50;
            text-align: center;
            border-radius: 4px;
            display: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Rank Changer</h2>
    <form id="rankChangeForm">
        <div class="form-group">
            <label for="sessid">Session ID (PHPSESSID):</label>
            <input type="text" id="sessid" name="sessid" required>
        </div>
        <div class="form-group">
            <label for="rank">Rank:</label>
            <select id="rank" name="rank" required>
                <option value="Administrateur">Administrateur</option>
                <option value="Support">Support</option>
                <option value="Vendeur">Vendeur</option>
                <option value="Premium">Premium</option>
                <option value="FREE">FREE</option>
            </select>
        </div>
        <div class="form-group">
            <label for="user">User ID:</label>
            <input type="text" id="user" name="user" required>
        </div>
        <div class="form-group">
            <label for="url">Base URL:</label>
            <input type="url" id="url" name="url" required>
        </div>
        <button type="button" class="btn" onclick="sendRequest()">Send</button>
    </form>
    <div id="notification" class="notification">Rank changed successfully!</div>
</div>

<script>
    function sendRequest() {
        var form = document.getElementById('rankChangeForm');
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', './source/repeater.php', true);

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    document.getElementById('notification').style.display = 'block';
                } else {
                    alert('Error: ' + response.http_code);
                }
            } else {
                alert('Request failed. Returned status of ' + xhr.status);
            }
        };

        xhr.send(formData);
    }
</script>

</body>
</html>
