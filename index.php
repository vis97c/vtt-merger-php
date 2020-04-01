<?php

$base = "WEBVTT
X-TIMESTAMP-MAP=LOCAL:00:00:00.000,MPEGTS:0

";

$logFile = "all.vtt";

// Log File
// file_put_contents($logFile, $log, FILE_APPEND);
for ($i = 1; $i < 332; $i++) {
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
            fseek($handle, 0, SEEK_END);

            // add the trailing comma
            fwrite($handle, '', 1);

            $segment = "./segments/segment" . $i . ".vtt";
            $segmentData = file_exists($segment) ? file_get_contents($segment) : "";
            // file_put_contents($hitCounter, $hit);
            // add the new json string
            fwrite($handle, substr($segmentData, 52));
        } else {
            // write the first event inside an array
            fwrite($handle, $base);
        }

        // close the handle on the file
        fclose($handle);
    }
}

// header('Content-Type: application/json');
// if (isset($_GET['logdebug'])) {
//     // Debug
//     // echo json_encode(array("current_request" => $r, "older_request" => json_decode(file_get_contents($logFile))));
// } else {
//     echo json_encode(array('logged' => true));
// }
