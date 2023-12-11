<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
        # bad request, no username or password
        http_response_code(400);
        exit;
    }
    
    $benutzername = $_POST['username'];
    $passwort = $_POST['password'];

    #echo "$benutzername";
    #echo "$passwort";

    $host = "localhost";
    $username = "g08";
    $password = "em28rust";
    $database = "g08";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        http_response_code(500);
        exit;
    }

    #echo "Successfully connected to DB!";

    $statement = $conn->prepare("SELECT pw, is_admin FROM users WHERE username = ?");
    $statement->bind_param("s", $benutzername);
    $statement->execute();
    $result = $statement->get_result();

    $statement->close();

    $conn->close();

    while($row = $result->fetch_assoc()){
        if ($row['pw'] === $passwort && $row['is_admin'] === 1) {
            # login successful
            http_response_code(200);
            exit;
        } 
    }

    # login failed / unauthorized
    http_response_code(401);
    exit;


} else {
    # no post request
    http_response_code(400);
    exit;
}

?>