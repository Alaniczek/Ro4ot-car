<?php
// stream.php - wklej IP z Serial Monitor
$esp32_url = "http://192.168.125.61/stream";

header('Content-Type: multipart/x-mixed-replace; boundary=frame');

$ch = curl_init($esp32_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {
    echo $data;
    flush();
    return strlen($data);
});
curl_exec($ch);
curl_close($ch);
?>
