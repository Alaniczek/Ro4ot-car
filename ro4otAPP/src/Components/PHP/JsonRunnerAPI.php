<?php
$f = 'Jsons/Runner.json'; //Path from index.php to this json :

if ($in = file_get_contents('php://input')) {
    // Dekodujemy i kodujemy ponownie z wcięciami
    file_put_contents($f, json_encode(json_decode($in), JSON_PRETTY_PRINT));
    exit;
}

$d = json_decode(@file_get_contents($f), true) ?? ['ip'=>'', 'rozkaz'=>''];
