<?php

function isPNGString($inputString) {
    $pattern = "/^\.\.\/images\/[a-zA-Z0-9_+\-.]+\.png$/";

    if (preg_match($pattern, $inputString)) {

        $dateiinfo = pathinfo($inputString);
        if (isset($dateiinfo['extension']) && strtolower($dateiinfo['extension']) === 'png') {
            return true;
        }
    }

    return false;
}



header('Access-Control-Allow-Origin: *');

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        http_response_code(400);
        exit;
    }

    if(!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])){
        # bad request, no username or password
        echo "username or password are not set!";
        http_response_code(400);
        exit;
    }


    # check if all fields are set
    if(!isset($_POST['image']) || !isset($_POST['title']) || !isset($_POST['author']) || !isset($_POST['price_netto']) || !isset($_POST['mwst']) || !isset($_POST['weight']) || !isset($_POST['stock']) || !isset($_POST['description']) || !isset($_POST['publisher'])){
        # bad request, not all fields set
        echo "Not all fields are set";
        http_response_code(400);
        exit;
    }
 
    if(empty($_POST['image']) || empty($_POST['title']) || empty($_POST['author']) || empty($_POST['price_netto']) || empty($_POST['weight']) || empty($_POST['stock']) || empty($_POST['description']) || empty($_POST['publisher'])){
        # bad request, necessary fields are empty
        echo "Necessary fields are empty!";
        http_response_code(400);
        exit;
    }


     #check if image is valid, string
     if((!is_string($_POST['image']) || strlen($_POST['image']) > 512 || is_numeric($_POST['image']))){
         # bad request, image is not string
         echo "image is no string or too long";
         http_response_code(400);
         exit;
     }

    # check if image is valid png string
    if(!isPNGString($_POST['image']) && !empty($_POST['image'])){
        echo "image is not a valid png string";
        http_response_code(400);
        exit;
    }
 
     #check if title is valid, string
     if((!is_string($_POST['title']) || strlen($_POST['title']) > 255 || is_numeric($_POST['image']))){
         # bad request, title is not string
         echo "title is no string or too long";
         http_response_code(400);
         exit;
     }
 
     #check if author is valid, string
     if((!is_string($_POST['author']) || strlen($_POST['author']) > 255 || is_numeric($_POST['image']))){
         # bad request, author is not string
         echo "author is no string or too long";
         http_response_code(400);
         exit;
     }
 
     # check if price_netto is valid
     if (!is_numeric($_POST['price_netto']) && !empty($_POST['price_netto'])) {
         echo "The price_netto is not a valid decimal or float.";
         http_response_code(400);
         exit;
     }
 
     # check if price_netto is not negative
     if(floatval($_POST['price_netto']) < 0){
         echo "The price_netto is negative.";
         http_response_code(400);
         exit;
     }
 
     # check if description is valid, string
     if((!is_string($_POST['description']) || strlen($_POST['description']) > 4096 || is_numeric($_POST['image']))){
         # bad request, description is not string
         echo "description is no string or too long";
         http_response_code(400);
         exit;
     }
 
     # check if publisher is valid, string
     if((!is_string($_POST['publisher']) || strlen($_POST['publisher']) > 255 || is_numeric($_POST['image']))){
         # bad request, publisher is not string
         echo "publisher is no string or too long";
         http_response_code(400);
         exit;
     }
 
     # check if price_netto is valid
     if (!is_numeric($_POST['mwst']) && !empty($_POST['mwst'])) {
         echo "The mwst is not a valid decimal or float.";
         http_response_code(400);
         exit;
     }
 
     # check if mwst is not negative
     if(floatval($_POST['mwst']) < 0){
         echo "The mwst is negative.";
         http_response_code(400);
         exit;
     }
 
     # check if weight is valid, int
     if(!is_numeric($_POST['weight']) && !empty($_POST['weight'])){
         echo "the weight is no int or negative or too large";
         http_response_code(400);
         exit;
     }
 
     if(floatval($_POST['weight']) < 0){
         echo "the weight is smaller than 0";
         http_response_code(400);
         exit;
     }
 
     # check if stock is valid, int (could be negative)
     if(!is_numeric($_POST['stock']) && !empty($_POST['stock'])){
         http_response_code(400);
         exit;
     }
 
     if(floatval($_POST['stock']) < 0){
         echo "the stock is smaller than 0";
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
    
    $json_data = json_decode($result, true);
    
    # Überprüfe is_admin
    if (!isset($json_data['is_admin']) || $json_data['is_admin'] !== '1') {
        echo "no admin";
        http_response_code(401);
        exit;
    }


    # insert into books
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

    $statement = $conn->prepare("INSERT INTO books (image, title, author, price_netto, mwst, weight, stock, description, publisher) VALUES (?, ?, ?, ?, COALESCE(?, DEFAULT(mwst)), ?, ?, ?, ?)");

    if (!$statement) {
        echo "Error while preparing the statement!";
        http_response_code(500);
        exit;
    }

    $mwst = ($_POST['mwst'] === '') ? null : $_POST['mwst'];

    $statement->bind_param("sssddiiss", $_POST['image'], $_POST['title'], $_POST['author'], $_POST['price_netto'], $mwst, $_POST['weight'], $_POST['stock'], $_POST['description'], $_POST['publisher']);

    $statement->execute();

    $conn->close();

    if ($statement->affected_rows > 0) {
        echo "successfull updated";
        $statement->close();
        http_response_code(200);
        exit;
    } 
    else {
        echo "Something went wrong!";
        $statement->close();
        http_response_code(500);
        exit;
    }

?>