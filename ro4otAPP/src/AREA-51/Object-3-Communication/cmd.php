<?php
// ==========================================
// KONFIGURACJA
// ==========================================
$ESP_IP   = '192.168.125.143'; // <--- WPISZ TU IP Z KROKU 1
$ESP_PORT = 4210;
// ==========================================

class NadajnikUDP {
    private $sock;
    private $ip;
    private $port;

    public function __construct($ip, $port) {
        $this->ip = $ip;
        $this->port = $port;
        $this->sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    }

    public function wyslij($znak) {
        if ($this->sock) {
            socket_sendto($this->sock, $znak, strlen($znak), 0, $this->ip, $this->port);
            return "Wysłano rozkaz: $znak do $this->ip";
        }
        return "Błąd gniazda UDP";
    }

    public function __destruct() {
        if ($this->sock) socket_close($this->sock);
    }
}

// Logika sterowania
if (isset($_GET['c'])) {
    $pilot = new NadajnikUDP($ESP_IP, $ESP_PORT);
    echo $pilot->wyslij($_GET['c']);
} else {
    echo "Backend gotowy.";
}
?>