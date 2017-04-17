<?php
/*
    header("HTTP/1.1 404 Not Found");
    die("Not implemented yet!");
*/

if (empty($path[1])) {
    // Default api page
    if ($downcount == 0) {
        $availability = "full";
    } else if ($downcount != 0 && $downcount <= 2) {
        $availability = "impacted";
    } else {
        $availability = "low";
    }

    if ($downcount == 0) {
        $allAvailable = true;
    } else {
        $allAvailable = false;
    }

    $json_string = array(
        http_code => 200,
        date => date(DATE_RFC2822),
        availability => $availability,
        all_avialable => $allAvailable,
        latency => getPing('google.de'),
        server_total => $servercount,
        server_up => $servercount - $downcount,
        server_down => $downcount, 
        server_up_percentage => round(($servercount - $downcount) / $servercount * 100),
        servers => getServerArray(),
    );

    header('Content-Type: application/json');
    echo json_encode($json_string, JSON_PRETTY_PRINT);
    unset($json_string);

} else if ($path[1] == "status") {
    // Server status handling
    if (!isset($path[2])) {
        // Display all Servers
        $json_string = array(
            http_code => 200,
            date => date(DATE_RFC2822),
            servers => getServerArray(),
        );

        header('Content-Type: application/json');
        echo json_encode($json_string, JSON_PRETTY_PRINT);
        unset($json_string);
    } else {
        foreach ($addresses as $key => $value) {
            if ($key == $path[2]) {
                $json_string =array(
                    http_code => 200,
                    date => date(DATE_RFC2822),
                    display => $disp[$key],
                    address => $addresses[$key],
                    port => $ports[$key],
                    available => $statuses[$key],
                );
                header('Content-Type: application/json');
                echo json_encode($json_string, JSON_PRETTY_PRINT);
                unset($json_string);
            }
        }
    }

} else if ($path[1] == "ping") {
    // ping handling
    $json_string = array(
        http_code => 200,
        date => date(DATE_RFC2822),
        latency => getPing('google.de'),
    );

    header('Content-Type: application/json');
    echo json_encode($json_string, JSON_PRETTY_PRINT);
    unset($json_string);

} else {
    // No api endpoint found
    header("HTTP/1.1 404 Not Found");

    $json_string = array(
        http_code => 404,
        reason => "API Endpoint not found",
        date => date(DATE_RFC2822),
    );
        
    header('Content-Type: application/json');
    echo json_encode($json_string, JSON_PRETTY_PRINT);
    unset($json_string);
}

function getServerArray () {
    global $addresses, $disp, $ports, $statuses;
    $arr = array();
    foreach ($addresses as $key => $value) {
        array_push($arr, array(
            display => $disp[$key],
            address => $addresses[$key],
            port => $ports[$key],
            available => $statuses[$key],
            )
        );
    }
    return $arr;
}