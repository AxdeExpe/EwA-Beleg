<?php

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        # no post request
        http_response_code(400);
        exit;
    }

    if(!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])){
        # bad request, no username or password
        http_response_code(400);
        exit;
    }

    # check if user is admin
    $url =  "http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/login.php";

    $data = array(
        'username' => $_POST['username'],
        'password' => $_POST['password']
    );

    $http = curl_init($url);

    curl_setopt($http, CURLOPT_POST, 1);
    curl_setopt($http, CURLOPT_POSTFIELDS, http_build_query($data)); // Hier wird der POST-Dateninhalt korrekt übergeben
    curl_setopt($http, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($http);
    $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
    curl_close($http);

    #echo $http_status;

    if($http_status !== 200){
        # user is not admin
        http_response_code($http_status);
        exit;
    }




    # user is admin

    # id not changeable, [needed for update]
    # image is changeable
    # title is changeable
    # author is changeable
    # price_netto is changeable
    # mwst is changeable
    # weight is changeable
    # stock is changeable
    # description is changeable
    # publisher is changeable

    # check if all fields are set
    if(!isset($_POST['id']) || !isset($_POST['image']) || !isset($_POST['title']) || !isset($_POST['author']) || !isset($_POST['price_netto']) || !isset($_POST['mwst']) || !isset($_POST['weight']) || !isset($_POST['stock']) || !isset($_POST['description']) || !isset($_POST['publisher']) || empty($_POST['id']) || empty($_POST['image']) || empty($_POST['title']) || empty($_POST['author']) || empty($_POST['price_netto']) || empty($_POST['mwst']) || empty($_POST['weight']) || empty($_POST['stock']) || empty($_POST['description']) || empty($_POST['publisher'])){
        # bad request, not all fields set
        echo "Not all fields are set";
        http_response_code(400);
        exit;
    }

    # check if id is valid, int
    if((!ctype_digit($_POST['id']) || $_POST['id'] < 0 || $_POST['id'] > 9999999999) || ($_POST['id'] === "NULL")){
        # bad request, id is not numeric
        echo "id is no int or negativ or too large";
        http_response_code(400);
        exit;
    }

    #check if image is valid, string
    if((!is_string($_POST['image']) || strlen($_POST['image']) > 512) && ($_POST['image'] !== "NULL")){
        # bad request, image is not string
        echo "image is no string or too long";
        http_response_code(400);
        exit;
    }

    #check if title is valid, string
    if((!is_string($_POST['title']) || strlen($_POST['title']) > 255) && ($_POST['title'] !== "NULL")){
        # bad request, title is not string
        echo "title is no string or too long";
        http_response_code(400);
        exit;
    }

    #check if author is valid, string
    if((!is_string($_POST['author']) || strlen($_POST['author']) > 255) && ($_POST['author'] !== "NULL")){
        # bad request, author is not string
        echo "author is no string or too long";
        http_response_code(400);
        exit;
    }

    # check if description is valid, string
    if((!is_string($_POST['description']) || strlen($_POST['description']) > 4096) && ($_POST['description'] !== "NULL")){
        # bad request, description is not string
        echo "description is no string or too long";
        http_response_code(400);
        exit;
    }

    # check if publisher is valid, string
    if((!is_string($_POST['publisher']) || strlen($_POST['publisher']) > 255) && ($_POST['publisher'] !== "NULL")){
        # bad request, publisher is not string
        echo "publisher is no string or too long";
        http_response_code(400);
        exit;
    }

    # check if mwst is valid, decimal
    if ((!preg_match('/^-?\d+(\.\d+)?$/', $_POST['mwst'])) && ($_POST['mwst'] !== "NULL")) {
        echo "The mwst is no decimal / float.";
        http_response_code(400);
        exit;
    }

    # check if price_netto is valid, decimal
    if ((!preg_match('/^-?\d+(\.\d+)?$/', $_POST['price_netto'])) && ($_POST['price_netto'] !== "NULL")) {
        echo "The mwst is no decimal / float.";
        http_response_code(400);
        exit;
    }

    # check if weight is valid, int
    if((!ctype_digit($_POST['weight']) || $_POST['weight'] < 0 || $_POST['weight'] > 9999999999) && ($_POST['weight'] !== "NULL")){
        echo "the weight is no int or negative or too large";
        http_response_code(400);
        exit;
    }

    # check if stock is valid, int (could be negative)
    if(!ctype_digit($_POST['stock']) && ($_POST['stock'] !== "NULL")){
        http_response_code(400);
        exit;
    }


    $host = "localhost";
    $username = "g08";
    $password = "em28rust";
    $database = "g08";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        http_response_code(500);
        exit;
    }


    
?>