<?php
// WPISZ TU IP KTÓRE POKAZAŁ ESP NA SERIALU!
$ip = '192.168.125.143'; 
$port = 4210;

$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

echo "Wysyłam 1 (Włącz)...\n";
socket_sendto($sock, "1", 1, 0, $ip, $port);
sleep(2); // Czekaj 2 sekundy

echo "Wysyłam 0 (Wyłącz)...\n";
socket_sendto($sock, "0", 1, 0, $ip, $port);

sleep(2); // Czekaj 2 sekundy

echo "Wysyłam 1 (Włącz)...\n";
socket_sendto($sock, "1", 1, 0, $ip, $port);
sleep(2); // Czekaj 2 sekundy

echo "Koniec testu.\n";
?>