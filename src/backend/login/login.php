<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $benutzername = $_POST['benutzername'];
    $passwort = $_POST['passwort'];


    if (empty($benutzername) || empty($passwort)) {

        echo "401"; # login failed
        exit;
    }


    # TODO check if user exists in DB
    $host = "localhost";
    $username = "g08";
    $password = "em28rust";
    $database = "g08";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Successfully connected to DB!";

    $statement = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $statement->bind_param("ss", $benutzername, $passwort);
    $statement->execute();
    $result = $statement->get_result();

    $statement->close();

    $conn->close();

    if($result){
        echo "200"; # login successful
        exit;
    }
    else{
        echo "401"; # login failed
        exit;
    }


} else {
    echo "400"; # no POST-Request
}

?>