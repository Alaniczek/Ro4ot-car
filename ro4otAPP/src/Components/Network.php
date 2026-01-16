<?php
class Network {
    private array $devices = [];
    private array $searchedDevices =[];
    private string $os;

    public function __construct()
    {
        $this->getOsUser();
        $this->scanNetwork();
    }

    private function getOsUser(): void
    {       
        $this->os = strtoupper(substr(PHP_OS, 0, 3));
    }

    private function scanNetwork(): void 
    {
        if ($this->os === 'WIN') {
            $this->windowsSystemScan();
        } else {
            $this->otherSystemScan();
        }
    }

    private function windowsSystemScan(): void
    {
        $output_local = shell_exec('arp -a'); 
        if ($output_local === null) return;

        $lines = explode("\n", $output_local);
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/(\d+\.\d+\.\d+\.\d+)\s+([0-9a-f-]{17})/i', $line, $matches)) {
                $this->devices[] = [ 
                    'ip' => $matches[1],
                    'mac' => strtoupper(str_replace('-', ':', $matches[2]))
                ];
            }
        }
    }

    private function otherSystemScan(): void
    {
        $output = shell_exec('arp -n'); 
        if ($output === null) return;

        $lines = explode("\n", $output);
        foreach ($lines as $line) {
            $cols = preg_split('/\s+/', trim($line));
            if (isset($cols[0], $cols[2]) && filter_var($cols[0], FILTER_VALIDATE_IP)) {
                if (preg_match('/([0-9a-f]{2}:){5}[0-9a-f]{2}/i', $cols[2])) {
                    $this->devices[] = [ 
                        'ip' => $cols[0],
                        'mac' => strtoupper($cols[2])
                    ];
                }
            }
        }
    }
    public function FindESP() : void 
    {
        if (empty($this->devices)) {
            return; 
        }
        $ESP_MACs_FACTORY = [ // THAT's OFFCIAL MAC ADRESS FOR ESP32,8266,CAM
            '24:6F:28', '30:AE:A4', '8C:AA:B5', 'AC:67:B2', 
            'BC:DD:C2', '40:22:D8', '48:3F:DA', 'D8:A0:1D',
            'EC:FA:BC', '54:5A:16', '60:01:94', 'A4:CF:12'
        ];
        $this->searchedDevices = [];

        foreach ($this->devices as $device)
        {
            echo $device['mac'] . "\n";
            foreach ($ESP_MACs_FACTORY as $ESP_MAC_FACTORY) 
            {
                if (str_starts_with($device['mac'], $ESP_MAC_FACTORY)) 
                {
                    $this->searchedDevices[] = $device;
                    break; 
                }
            }
        }
    }

    public function getSearchedDevices(): array
    {
        return $this->searchedDevices;
    }

    public function getDevices(): array
    {
        return $this->devices;
    }
}


/*TESTS */
$net = new Network();
$listaUrządzeń = $net->getDevices();
$ESP_FINDER = $net->FindESP();
$ESP_FOUND = $net->getSearchedDevices();


echo "Wykryte urządzenia w sieci:\n";
echo "\n";
print_r($ESP_FOUND);

?>