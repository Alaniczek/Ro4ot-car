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
    public function FindESP() :void //In progress
    {
        if($devices == null) exit;
        foreach ($devices as $device)
        {
            echo $device['mac'];
        }
    }

    

    public function getDevices(): array
    {
        return $this->devices;
    }
}



$net = new Network();
$listaUrządzeń = $net->getDevices();

echo "Wykryte urządzenia w sieci:\n";
print_r($listaUrządzeń);
echo "\n";
print_r($net->FindESP());

?>