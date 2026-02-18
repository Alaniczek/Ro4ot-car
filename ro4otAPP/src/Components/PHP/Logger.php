<?php

class Logger
{
    private $path = '../../Jsons/LogHistory.json';

    public function changePath($newPath)
    {
        $this->path = $newPath;
    }

    public function log($event){
        $currentData = file_exists($this->path) ? json_decode(file_get_contents($this->path), true) : [];

        $newEntry = [
            'data' => date('Y-m-d H:i:s'),
            'zdarzenie' => $event,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
            ]
        ;
        $currentData[] = $newEntry;
        file_put_contents($this->path, json_encode($currentData, JSON_PRETTY_PRINT));
    }
    public function clearLog(){
        file_put_contents($this->path, json_encode([]));
    }
}

// $Log = new Logger();
// $Log->log("Strona główna odwiedzona");
// $Log->log("Inne zdarzenie");

// $Log->clearLog(); 