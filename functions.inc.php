<?php
/*
This file contains functions for the Line-Lan.net Status page
*/

// Check the Status of a Webserver
function isPingable($host, $port) {
    if ($socket = @fsockopen($host, $port, $errno, $errstr, 2)) {
        fclose($socket);
        return true;
    } else {
        return false;
    }
}

// Check the latency to a Webserver
function getPing($domain) {
    $starttime = microtime(true);
    $file = @fsockopen($domain, 80, $errno, $errstr, 10);
    $stoptime = microtime(true);
    $status = 0;
    if (!$file) {
        $status = -1;
    } else {
        fclose($file);
        $fstatus = ($stoptime - $starttime) * 1000;
        $status = floor($fstatus);
    }
    return $status;
}
