<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort'];

    if (empty($benutzername) || empty($passwort)) {

        echo "false";
        exit;

    }

    if($benutzername === "admin" && $passwort = "adm24"){
        echo "true";
        exit;
    }


} else {
    echo "false"; # keine POST-Request
}

?>