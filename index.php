<?php
ini_set('max_execution_time', '0');
// header('Content-Type: application/json');
// if (isset($_GET['logdebug'])) {
//     // Debug
//     // echo json_encode(array("current_request" => $r, "older_request" => json_decode(file_get_contents($logFile))));
// } else {
//     echo json_encode(array('logged' => true));
// }
if (isset($_POST['url']) && isset($_POST['prefix'])) {
    if (empty($_POST['url'])) {
        // throw new ErrorException('Url por favor.');
        unset($_POST);
        echo 'Url por favor.';
        echo '<br/>';
        echo '<a href=".">Reintentar</a>.';
        die();
    }

    if (empty($_POST['prefix'])) {
        // throw new ErrorException('Prefijo por favor.');
        unset($_POST);
        echo 'Prefijo por favor.';
        echo '<br/>';
        echo '<a href=".">Reintentar</a>.';
        die();
    }
    //  $fromPerson = '+from%3A'.$_POST['fromPerson'];
    //  echo $fromPerson;
    $base = "WEBVTT
X-TIMESTAMP-MAP=LOCAL:00:00:00.000,MPEGTS:0

";
    $prefix = $_POST['prefix'];
    $url = explode($prefix, $_POST['url']);
    if (count($url) != 2) {
        // throw new ErrorException('Url o prefijo inadecuado.');
        unset($_POST);
        echo 'Url o prefijo inadecuado.';
        echo '<br/>';
        echo '<a href=".">Reintentar</a>.';
        die();
    }
    $partOne = $url[0];
    $partTwo = explode(".vtt", $url[1])[1];
    $error = false;
    $index = 1;

    while ($error == false) {
        // $url = 'http://example.com';
        $segment = $partOne . $prefix . $index . ".vtt" . $partTwo;
        $ch = curl_init($segment);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            //             $result .= "
            // hola?";
            // $error = true;
            if ($index == 1) {
                // throw new ErrorException('Archivo inexistente y/o acceso bloqueado, puede que haga falta incluir un token en l url.');
                unset($_POST);
                echo 'Archivo inexistente y/o acceso bloqueado, puede que haga falta incluir un token en l url.';
                echo '<br/>';
                echo '<a href=".">Reintentar</a>.';
            }
            die();
        }
        curl_close($ch);

        //         $logFile = "log.txt";

        //         // Log File
        //         // file_put_contents($logFile, $log, FILE_APPEND);
        //         // read the file if present
        //         $handle = @fopen($logFile, 'r+');

        //         // create the file if needed
        //         if ($handle === false) {
        //             $handle = fopen($logFile, 'w+');
        //         }

        //         if ($handle) {
        //             // seek to the end
        //             fseek($handle, 0, SEEK_END);

        //             // are we at the end of is the file empty
        //             if (ftell($handle) > 0) {
        //                 // move back a byte
        //                 fseek($handle, 0, SEEK_END);

        //                 // add the trailing comma
        //                 fwrite($handle, '', 1);

        //                 // file_put_contents($hitCounter, $hit);
        //                 // add the new json string
        //                 fwrite($handle, "
        // " . $index . ":
        // " . $result);
        //             } else {
        //                 // write the first event inside an array
        //                 fwrite($handle, $base);
        //             }

        //             // close the handle on the file
        //             fclose($handle);
        //         }


        if (stripos($result, "Error 4") !== false) {
            break;
        }
        $index++;
        $base .= substr($result, 52);
    }
    header('Content-type: text/vtt');
    header('Content-Disposition: attachment; filename="subtitulo.vtt"');
    echo $base;
    die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>.vtt merger</title>
</head>

<body>
    <h1>.vtt merger</h1>
    <br /><br />
    <form action="" method="post">
        <label for="name">Url completa de uno de los vtt</label>
        <br />
        <input id="name" type="text" placeholder="Url de archivo vtt" name="url">
        <br /><br />
        <label for="prefix">Prefijo del archivo, si el archivo se llama "segment17.vtt" el prefijo sera "segment"</label>
        <br />
        <input id="prefix" type="text" placeholder="Prefijo del vtt" name="prefix">
        <br /><br />
        <label for="submit">Recuerda que esto puede tomar algunos minutos segun la cantidad de archivos</label>
        <br />
        <input id="submit" type="submit" value="Obtener subtitulos">
    </form>
</body>

</html>