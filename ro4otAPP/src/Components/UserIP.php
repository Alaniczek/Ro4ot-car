<?php

class User_IP_local {
    private string $userIP = "";

    public function getUserIP(): void
    {
        $this->userIP = gethostbyname(gethostname());
    }

    public function giveUserIP(): string
    {
        return $this->userIP;
    }
}