<?php

$logFile = "log.json";
$hitCounter = "counter.txt";

// Hit Counter
$hit = file_exists($hitCounter) ? file_get_contents($hitCounter) : 0;
$hit++;
file_put_contents($hitCounter, $hit);

$post = file_get_contents("php://input");
// Log Report
$log = array(
    'hit' => $hit,
    'time' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR'],
    'uri' => $_SERVER['REQUEST_URI'],
    'port' => $_SERVER["REMOTE_PORT"],
    'agent' => isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "",
    'referer' => isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "",
    'script' => $_SERVER["SCRIPT_FILENAME"],
    'query' => $_SERVER["QUERY_STRING"],
    'post' => $post,
    'method' => $_SERVER["REQUEST_METHOD"],
    'host' => $_SERVER["HTTP_HOST"],
    'cookie' => $_SERVER["HTTP_COOKIE"],
    'via' => $_SERVER["HTTP_VIA"],
    'forwarded' => $_SERVER["HTTP_X_FORWARDED_FOR"],
);

// Log File
// file_put_contents($logFile, $log, FILE_APPEND);

// read the file if present
$handle = @fopen($logFile, 'r+');

// create the file if needed
if ($handle === false) {
    $handle = fopen($logFile, 'w+');
}

if ($handle) {
    // seek to the end
    fseek($handle, 0, SEEK_END);

    // are we at the end of is the file empty
    if (ftell($handle) > 0) {
        // move back a byte
        fseek($handle, -1, SEEK_END);

        // add the trailing comma
        fwrite($handle, ',', 1);

        // add the new json string
        fwrite($handle, json_encode($log) . ']');
    } else {
        // write the first event inside an array
        fwrite($handle, json_encode(array($log)));
    }

    // close the handle on the file
    fclose($handle);
}

header('Content-Type: application/json');
if (isset($_GET['logdebug'])) {
    // Debug
    echo json_encode(array("current_request" => $r, "older_request" => json_decode(file_get_contents($logFile))));
} else {
    echo json_encode(array('logged' => true));
}
