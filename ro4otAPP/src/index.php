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

    <script>
        document.getElementById('btnGetIP').addEventListener('click', function() {
            fetch('Components/UserIP.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('displayIP').innerText = data.ip;
                })
                .catch(error => console.error('Błąd:', error));
        });
    </script>
    <button></button>

</body>
</html>