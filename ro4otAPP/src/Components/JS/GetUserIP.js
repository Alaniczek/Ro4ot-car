document.getElementById('btnGetIP').addEventListener('click', function() {  //ITS LOCAL IP!!!!!
            fetch('Components/UserIP.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('displayIP').innerText = data.ip;
                })
                .catch(error => console.error('Błąd:', error));
        });