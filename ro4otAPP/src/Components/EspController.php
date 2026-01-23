<?php
class EspController{
    private string $EspIP;
    private string $LastCommand = "";

    
    public function __construct(string $IP)
    {
        $this->EspIP = $IP;
    }

    public function sendCommand($Command)
    {
        $url = "http://$this->EspIP/send?val=" . urlencode($Command);
    }
    // public function readResponse(): string
    // {
    //     $response = file_get_contents($url);
    // }


}