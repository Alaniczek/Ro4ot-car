<?php
// --- KONFIGURACJA ---
require_once 'Components/PHP/Logger.php';

$esp_ip = '192.168.125.34'; // ESP IP :> 
$esp_port = 4210;
$plik_logow = 'logi.txt';

$Logger = new Logger();
$Logger->changePath('Jsons/LogHistory.json');

// OD ESP
if (isset($_GET['msg'])) {
    $wpis = date("H:i:s") . " [ESP -> PHP]: " . $_GET['msg'] . "\n";
    file_put_contents($plik_logow, $wpis, FILE_APPEND);
    $Logger->log("ESP wysłał wiadomość: " . $_GET['msg']);
    exit;
}

// DO ESP
if (isset($_POST['akcja'])) {
    $cmd = $_POST['akcja'];

    if ($cmd === 'clear') {
        file_put_contents($plik_logow, "");
        $Logger->clearLog();
    } 
    else {
        $wpis = date("H:i:s") . " [PHP -> ESP]: Wyslano komende " . $cmd . "\n";
        file_put_contents($plik_logow, $wpis, FILE_APPEND);
        $Logger->log("Wysłano komendę do ESP: " . $cmd);

        // Wyślij UDP do robota
        $sock = fsockopen("udp://$esp_ip", $esp_port);
        if ($sock) {
            fwrite($sock, $cmd);
            fclose($sock);
        }
    }

    // Odśwież stronę (czyści formularz przed F5)
    header("Location: " . $_SERVER['PHP_SELF']); 
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sterowanie Robotem</title>
</head>
<body>

    <h1>Panel Sterowania</h1>

    <form method="post">
        <button type="submit" name="akcja" value="1" style="font-size: 20px; padding: 10px; background: #90EE90;">WLACZ LED</button>
        <button type="submit" name="akcja" value="0" style="font-size: 20px; padding: 10px; background: #FFB6C1;">WYLACZ LED</button>

        <button type="submit" name="akcja" value="clear" style="font-size: 20px; padding: 10px; background: #D3D3D3; float: right;">LOG CLEAR</button>
    </form>

    <hr>
    <h3>LOGI SYSTEMOWE</h3>
    <textarea style="width: 100%; height: 300px; font-family: monospace;">
<?php 
    if (file_exists($plik_logow)) {
        echo file_get_contents($plik_logow);
    }
?>
    </textarea>

    <script>
        setTimeout(function(){ location.reload(); }, 5000);
    </script>

</body>
</html>