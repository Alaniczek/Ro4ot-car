<?php
require_once '../src/Components/UserIP.php';

$user = new User_IP_local();

$user->getUserIP();

echo "Twoje LOKALNE IP to: " . $user->giveUserIP();