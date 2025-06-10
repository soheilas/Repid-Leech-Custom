<?php
function checkClientIP($clientIP) {
    $clientsFile = '/var/www/licensing/data/clients.txt';

    if (!file_exists($clientsFile)) {
        return false;
    }

    $lines = file($clientsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;

        $parts = explode(',', $line);
        if (count($parts) >= 3) {
            $ip = trim($parts[0]);
            $expiry = trim($parts[1]);
            $active = trim($parts[2]);

            if ($ip === $clientIP && $active === '1') {
                if (strtotime($expiry) > time()) {
                    return true;
                }
            }
        }
    }
    return false;
}

$clientIP = $_SERVER['REMOTE_ADDR'];
echo checkClientIP($clientIP) ? 'allowed' : 'denied';
?>
