<?php

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

if(!isset($_POST['id']) || empty($_POST['id'])){
    # bad request, no book_id
    echo "id is not set!";
    http_response_code(400);
    exit;
}


# check if id is valid, int
if((!ctype_digit($_POST['id']) || $_POST['id'] < 0 || $_POST['id'] > 9999999999)){
    # bad request, id is not numeric
    echo "id is no int or negativ or too large";
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



# user is admin
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


$stmt = $conn->prepare("DELETE FROM books WHERE id = ?");

if (!$stmt) {
    echo "Fehler beim Laden von UTF-8 " . $conn->error;
    http_response_code(500);
    exit;
}

$stmt->bind_param("i", $_POST['id']);
$stmt->execute();

if($stmt->affected_rows === 0){
    echo "no book with this id";
    http_response_code(404);
    exit;
}

$stmt->close();
$conn->close();

http_response_code(200);
exit;

?>