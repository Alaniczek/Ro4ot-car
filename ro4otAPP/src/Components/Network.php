<?php
class Network {
    private string $os;
    private string $ESP_IP;

    public function __construct()
    {
        $this->getOsUser();
    }

    private function getOsUser(): void
    {       
        $this->os = strtoupper(substr(PHP_OS, 0, 3));
    }

    public function SetESPIP($ip)   
    {
        $this->ESP_IP = $ip;
    }

    

    // $output_local = shell_exec('arp -a'); 
    // if ($output_local === null) return;
    // $lines = explode("\n", $output_local);
    // foreach ($lines as $line) {
    //     $line = trim($line);
    //     if (preg_match('/(\d+\.\d+\.\d+\.\d+)\s+([0-9a-f-]{17})/i', $line, $matches)) {
    //         $this->devices[] = [ 
    //             'ip' => $matches[1],
    //             'mac' => strtoupper(str_replace('-', ':', $matches[2]))
    //         ];
    //     }
    // }
}


// /*TESTS */
// $net = new Network();
// $listaUrządzeń = $net->getDevices();
// $ESP_FINDER = $net->FindESP();
// $ESP_FOUND = $net->getSearchedDevices();


// echo "Wykryte urządzenia w sieci:\n";
// echo "\n";
// print_r($ESP_FOUND);

?>