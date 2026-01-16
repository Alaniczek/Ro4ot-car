<?php
header('Content-Type: application/json');

$os = strtoupper(substr(PHP_OS, 0, 3));
$command = ($os === 'WIN') ? 'arp -a' : 'arp -n';

$output = shell_exec($command);
$devices = [];

if (empty($output)) {
    echo json_encode([]);
    exit;
}

if ($os === 'WIN') {
    $lines = explode("\n", $output);
    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/(\d+\.\d+\.\d+\.\d+)\s+([0-9a-f-]{17})/i', $line, $matches)) {
            $devices[] = [
                'ip' => $matches[1],
                'mac' => strtoupper(str_replace('-', ':', $matches[2]))
            ];
        }
    }
} else 
{ //If you use linux, uncomment it :> 
    // Linux
    $lines = explode("\n", $output);
    foreach ($lines as $line) {
        $cols = preg_split('/\s+/', trim($line));
        if (isset($cols[0], $cols[2]) && filter_var($cols[0], FILTER_VALIDATE_IP)) {
            if (preg_match('/([0-9a-f]{2}:){5}[0-9a-f]{2}/i', $cols[2])) {
                $devices[] = [
                    'ip' => $cols[0],
                    'mac' => strtoupper($cols[2])
                ];
            }
        }
    }
}

echo json_encode(array_values(array_unique($devices, SORT_REGULAR)));