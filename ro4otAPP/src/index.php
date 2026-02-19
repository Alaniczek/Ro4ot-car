<?php
// --- KONFIGURACJA ---
require_once 'Components/PHP/Logger.php';
require_once 'Components/PHP/CommandManager.php';

$esp_ip = '192.168.125.34'; // ESP IP :> 
$esp_port = 4210;
$plik_logow = 'logi.txt';


$Logger = logger::getInstance();
$Logger->changePath('Jsons/LogHistory.json');

// OD ESP
if (isset($_GET['msg'])) {
    $wpis = date("H:i:s") . " [ESP -> PHP]: " . $_GET['msg'] . "\n";
    file_put_contents($plik_logow, $wpis, FILE_APPEND);
    $Logger->log("ESP wysłał wiadomość: " . $_GET['msg']);
    exit;
}

$CommandManager = new CommandManager($esp_ip, $esp_port, $Logger);

if (isset($_POST['action'])) {
    $cmd = $_POST['action'];

    if ($cmd === 'clear') {
        file_put_contents($plik_logow, "");
        $Logger->clearLog();
    } 
    else {
        $wpis = date("H:i:s") . " [PHP -> ESP]: Wyslano komende " . $cmd . "\n";
        file_put_contents($plik_logow, $wpis, FILE_APPEND);
        $Logger->log("Wysłano komendę do ESP: " . $cmd);
        
        $CommandManager->sendCommand($cmd);
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
    <script src="Components/JS/ButtonMaker.js"></script>

    <h1>Panel Sterowania</h1>

    <form method="post">
        <div class="CommandButtons"></div>
        <button type="submit" name="action" value="1" style="font-size: 20px; padding: 10px; background: #90EE90;">WLACZ LED</button>
        <button type="submit" name="action" value="0" style="font-size: 20px; padding: 10px; background: #FFB6C1;">WYLACZ LED</button>

        <button type="submit" name="action" value="clear" style="font-size: 20px; padding: 10px; background: #D3D3D3; float: right;">LOG CLEAR</button>
    </form>
    
    <hr>
   
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
        const myButton = new ButtonMakerFromJSON('Jsons/Command.json?v=' + Date.now());        
        myButton.render('.CommandButtons');
    </script>
    <script>
        setTimeout(function(){ location.reload(); }, 5000);
    </script>

</body>
</html>

<!-- TEST -->