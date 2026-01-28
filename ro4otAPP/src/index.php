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
    <input type="text" name="ESPIP" id="ESPIP">
    <p><span id="displayESPIP"></span></p>

    <!-- <div id="moj-stream">
        <img src="stream.php" style="width: 100%; height: auto;">
    </div> -->

    





    <script> // USER IP O:
        document.getElementById('btnGetIP').addEventListener('click', function() {
            fetch('Components/UserIP.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('displayIP').innerText = data.ip;
                })
                .catch(error => console.error('Błąd:', error));
        });
    </script>
    <script> // ESP IP . _. 
        const input = document.getElementById('ESPIP');
        const output = document.getElementById('displayESPIP');
        
        input.oninput = () => output.innerText = input.value || '—';
    </script>
</body>
</html>