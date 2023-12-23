<?php

header('Access-Control-Allow-Origin: *');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    # no post request
    http_response_code(400);
    exit;
}

if(!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['email']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])){
    # bad request, no username or password or email
    http_response_code(400);
    exit;
}

# check if email is valid
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    # bad request, email is not valid
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

if (!$conn->set_charset("utf8mb4")) {
    echo "Fehler beim Laden von UTF-8 " . $conn->error;
    http_response_code(500);
    exit;
}



# check if user already exists

$statement = $conn->prepare("SELECT * FROM Users WHERE email = ?");

if(!$statement){
    http_response_code(500);
    exit;
}

$statement->bind_param("s", $_POST['email']);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows !== 0) {
    $conn->close();
    echo "User already exists!";
    http_response_code(409);
    exit;
}

$statement->close();



$statement = $conn->prepare("SELECT * FROM Users WHERE username = ?");

if(!$statement){
    http_response_code(500);
    exit;
}

$statement->bind_param("s", $_POST['username']);
$statement->execute();
$result = $statement->get_result();

if ($result->num_rows !== 0) {
    $conn->close();
    echo "User already exists!";
    http_response_code(409);
    exit;
}

$statement->close();

# insert user into DB

$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

$statement = $conn->prepare("INSERT INTO Users (username, pw, email) VALUES (?, ?, ?)");
$statement->bind_param("sss", $_POST['username'], $hashedPassword, $_POST['email']);
$statement->execute();

if ($statement->affected_rows === 0) {
    $conn->close();
    echo "Error while inserting the data!";
    http_response_code(500);
    exit;
}

$statement->close();
$conn->close();

http_response_code(200);
exit;

?>