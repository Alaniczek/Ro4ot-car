<?php
$esp_ip = "10.40.30.253";
$data = "321";

$url = "http://$esp_ip/send?val=" . urlencode($data);
$response = file_get_contents($url);

if ($response !== false) {
    echo "Wysłano: $data | Odpowiedź: $response";
} else {
    echo "Błąd połączenia";
}
?>