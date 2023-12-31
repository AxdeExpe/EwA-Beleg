<?php

    header('Access-Control-Allow-Origin: *');


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(400);
        exit;
    }


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


    if (!$conn->set_charset("utf8mb4")) {
        echo "Fehler beim Laden von UTF-8 " . $conn->error;
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
        if (password_verify($passwort, $row['pw']) && $row['is_admin'] === 1) {
            # login successful
            http_response_code(200);
            echo json_encode(array("is_admin" => "1"));
            exit;
        }
        if (password_verify($passwort, $row['pw']) && $row['is_admin'] === 0) {
            # login successful
            http_response_code(200);
            echo json_encode(array("is_admin" => "0"));
            exit;
        } 
    }

    # login failed / unauthorized
    http_response_code(401);
    exit;


?>