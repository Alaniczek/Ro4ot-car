<?php
include 'Components/PHP/JsonRunnerAPI.php';
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Pobierz IP</title>
</head>
<body>

    <button id="btnGetIP">Pokaż moje IP</button>
    <p>Twoje IP to: <span id="displayIP">...</span></p>
    <span>Wpisz IP ESP</span>
    
    
    <!-- <input type="text" name="ESPIP" id="ESPIP">
    <p><span id="displayESPIP"></span></p> -->

    <!-- <div id="moj-stream">
        <img src="stream.php" style="width: 100%; height: auto;">
    </div> -->


    <input id="ip" value="<?= $d['ip'] ?? '' ?>" oninput="s()" placeholder="IP">
    <input id="rozkaz" value="<?= $d['rozkaz'] ?? '' ?>" oninput="s()" placeholder="Rozkaz">

    




    <script>
    function s() {
        fetch('', {
            method: 'POST',
            body: JSON.stringify({
                ip: document.getElementById('ip').value,
                rozkaz: document.getElementById('rozkaz').value
            })
        });
    }
    </script>

    <script src="Components/JS/GetUserIP.js"></script>
    <script> // ESP IP . _. 
        const input = document.getElementById('ESPIP');
        const output = document.getElementById('displayESPIP');
        
        input.oninput = () => output.innerText = input.value || '—';
    </script>
</body>
</html>