<?php


class User_IP 
{
    private string $userIP;

    public function __construct()
    {
        $ip = gethostbyname(gethostname());

        if ($ip === '127.0.0.1' || $ip === '127.0.1.1') {
            $output = shell_exec("hostname -I");
            if ($output) {
                $ips = explode(' ', trim($output));
                $ip = $ips[0];
            }
        }

        $this->userIP = $ip;
    }

    public function giveUserIP(): string
    {
        return $this->userIP;
    }
}
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $userIp = new User_IP();
    echo json_encode(['ip' => $userIp->giveUserIP()]);
}