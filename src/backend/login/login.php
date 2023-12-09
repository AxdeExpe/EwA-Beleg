<?php

#error_reporting(E_ALL);
#ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo "no username or password";
        echo "400"; # bad request
        exit;
    }
    
    $benutzername = $_POST['username'];
    $passwort = $_POST['password'];

    echo "$benutzername";
    echo "$passwort";


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

    #echo "Successfully connected to DB!";

    $statement = $conn->prepare("SELECT pw FROM users WHERE username = ?");
    $statement->bind_param("s", $benutzername);
    $statement->execute();
    $result = $statement->get_result();

    $statement->close();

    $conn->close();

    while($row = $result->fetch_assoc()){
        if ($row['pw'] === $passwort) {
            echo "200"; # login successful
            exit;
        } 
    }

    echo "401"; # login failed
    exit;


} else {
    echo "no post request";
    echo "400"; # no POST-Request
}

?>