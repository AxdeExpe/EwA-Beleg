<?php

###################################################################
#                                                                 #
# I and God once understood this code, now only God does.         #
# Type here your attempts to comprehend this code: 3              #
#                                                                 #
###################################################################


$stripe_api_key = "sk_test_51OREORC36J02THDSJqRzCyfAOimB3RTMMOb5j6126e3Yx69FDre0gbMkHz04Ak4Kb3XjIY9sWGdbju60MOVck9WZ00IVbnW19S";

header('Access-Control-Allow-Origin: *');
require('./stripe-php-master/init.php');

if($_SERVER['REQUEST_METHOD'] !== 'GET'){
    # no get request
    http_response_code(400);
    exit;
}

if($_GET['session_id'] === null || empty($_GET['session_id'])){
    # no session id
    http_response_code(400);
    exit;
}


$jsonData = json_decode($_GET['data'], true);

#echo $_GET['data'];


if ($jsonData === null || json_last_error() !== JSON_ERROR_NONE) {
    echo 'No valid JSON data found in request. 1';
    http_response_code(400);
    exit;
}

if (!is_array($jsonData) || count($jsonData) < 0 || !is_array($jsonData[0])) {
    echo 'No valid JSON Array found in request. 2';
    http_response_code(400);
    exit;
}

$numberOfArrays = count($jsonData) - 1;

if($numberOfArrays < 1){
    echo 'No valid JSON Array found in request. 3';
    http_response_code(400);
    exit;
}


# check if all keys of each array have legitimate values
for($i = 1; $i <= $numberOfArrays; $i++){
    if (!isset($jsonData[$i]['id']) || !isset($jsonData[$i]['amount']) || !is_numeric($jsonData[$i]['id']) || !is_numeric($jsonData[$i]['amount'])) {
        echo 'Invalid values found in JSON data.';
        http_response_code(400);
        exit;
    }
}

# login part
# check if user is no admin
$url =  "http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/login.php";

$data = array(
    'username' => $jsonData[0]['username'],
    'password' => $jsonData[0]['password']
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
if (!isset($json_data['is_admin']) || $json_data['is_admin'] !== '0') {
    echo "no admin";
    http_response_code(401);
    exit;
}



# check if all keys of each array has legit values

for($i = 1; $i <= $numberOfArrays; $i++){
    if(!isset($jsonData[$i]['id']) || !isset($jsonData[$i]['amount']) || empty($jsonData[$i]['id']) || empty($jsonData[$i]['amount'])){
        # bad request, no id or amount
        echo $jsonData[$i]['id'];
        echo "No id or amount found in request.";
        http_response_code(400);
        exit;
    }

    if(!is_numeric($jsonData[$i]['id']) || $jsonData[$i]['id'] < 0 || $jsonData[$i]['id'] > 9999999999){
        # bad request, book_id is not numeric
        echo "Book id is not numeric.";
        http_response_code(400);
        exit;
    }

    if(!is_numeric($jsonData[$i]['amount']) || $jsonData[$i]['amount'] < 0 || $jsonData[$i]['amount'] > 9999999999){
        # bad request, amount is not numeric
        echo "Amount is not numeric.";
        http_response_code(400);
        exit;
    }

    # echo $jsonData[$i]['id'];
}



# select all needed books from database
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



# check if transaction already exists

$statement = $conn->prepare("SELECT * FROM Orders WHERE stripe_checkout_session_id = ?");

if(!$statement){
    http_response_code(500);
    exit;
}

$statement->bind_param("s", $_GET['session_id']);
$statement->execute();
$result = $statement->get_result();

$statement->close();

if($result->num_rows > 0){
    $conn->close();
    echo "Transaction already exists!";
    http_response_code(409);
    exit;
}


for($j = 1; $j <= $numberOfArrays; $j++){
    $statement = $conn->prepare("SELECT * FROM Books WHERE id = ?");
    
    if(!$statement){
        http_response_code(500);
        exit;
    }

    $statement->bind_param("i", $jsonData[$j]['id']);
    $statement->execute();
    $result = $statement->get_result();

    $statement->close();

    if($result->num_rows <= 0){
        $conn->close();
        http_response_code(404);
        exit;
    }

    $row = $result->fetch_assoc();

    if($row['stock'] < $jsonData[$j]['amount']){
        $conn->close();
        echo "Not enough in stock!";
        http_response_code(409);
        exit;
    }
}


$api_error = null;
$session_id = $_GET['session_id'];

$api_url = "https://api.stripe.com/v1/checkout/sessions/{$session_id}";

// cURL-Initialisierung
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $stripe_api_key,
));

// API-Aufruf durchführen
$response = curl_exec($ch);

// Überprüfe, ob ein Fehler aufgetreten ist
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_status !== 200) {
    echo "API-Fehler: HTTP-Status $http_status";
    http_response_code(500);
    exit;
}

$checkout_session = json_decode($response);

if ($checkout_session === null) {
    echo "Invalid transaction!";
    http_response_code(500);
    exit;
}

$customer_details = $checkout_session->customer_details;
$paymentIntent = null;

try { 
    // Ersetze die Stripe-Bibliothek durch direkten API-Aufruf
    $ch = curl_init("https://api.stripe.com/v1/payment_intents/{$checkout_session->payment_intent}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $stripe_api_key,
    ));

    $response = curl_exec($ch);

    // Überprüfe, ob ein Fehler aufgetreten ist
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_status !== 200) {
        echo "API-Fehler: HTTP-Status $http_status";
        http_response_code(500);
        exit;
    }

    $paymentIntent = json_decode($response);
} catch (Exception $e) {
    $api_error = $e->getMessage();
    echo $api_error;
    http_response_code(500);
    exit;
}

if ($paymentIntent === null || $paymentIntent->status !== 'succeeded') {
    echo "Transaction has been failed!";
    http_response_code(500);
    exit;
}

$transactionID = $paymentIntent->id;
$paidAmount = $paymentIntent->amount / 100;
$paidCurrency = $paymentIntent->currency;
$payment_status = $paymentIntent->status;

// Customer info
$customer_name = $customer_email = '';

if (!empty($customer_details)) {
    $customer_name = !empty($customer_details->name) ? $customer_details->name : '';
    $customer_email = !empty($customer_details->email) ? $customer_details->email : '';
}


// Check if any order data is exists already with the same TXN ID
$statement = $conn->prepare("SELECT * FROM Orders WHERE txn_id = ?");

if(!$statement){
    http_response_code(500);
    exit;
}

$statement->bind_param("s", $transactionID);
$statement->execute();
$result = $statement->get_result();
$prevRow = $result->fetch_assoc();

$statement->close();

# insert into the purchases into Orders
$conn->begin_transaction();


$hashedPassword = password_hash($jsonData[0]['password'], PASSWORD_DEFAULT);

for($i = 1; $i <= $numberOfArrays; $i++){
    // Insert transaction data into the database 
    $sql = "INSERT INTO Orders (book_id, order_date, amount, price, modified, stripe_checkout_session_id, txn_id, customer_name, customer_email, user_username)
        SELECT
            b.id AS book_id,
            NOW() AS order_date,
            ? AS amount,
            b.price_brutto * ? AS price,
            NOW() AS modified,
            ? AS stripe_checkout_session_id,
            ? AS txn_id,
            ? AS customer_name,
            ? AS customer_email,
            ? AS user_username
        FROM Books b
        WHERE b.id = ?";

    $stmt = $conn->prepare($sql); 
        
    if(!$stmt){
        http_response_code(500);
        exit;
    }
        
    $stmt->bind_param("iisssssi", $jsonData[$i]['amount'], $jsonData[$i]['amount'], $_GET['session_id'], $transactionID, $customer_name, $customer_email, $jsonData[0]['username'], $jsonData[$i]['id']); 
    $stmt->execute(); 
        
    if($stmt->affected_rows <= 0){
        echo "Error while inserting into Orders!";
        echo $stmt->error;
        $conn->rollback();
        $conn->close();
        http_response_code(500);
        exit;
    }

    $stmt->close();
}

$conn->commit();

# echo "Successfully inserted into Orders!";

for($i = 1; $i <= $numberOfArrays; $i++){

    // Führe die Aktualisierung der Books-Tabelle aus
    $updateSql = "UPDATE Books SET stock = stock - ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);

    if (!$updateStmt) {
        http_response_code(500);
        exit;
    }

    $updateStmt->bind_param("ii", $jsonData[$i]['amount'], $jsonData[$i]['id']);
    $updateStmt->execute();

    if($updateStmt->affected_rows <= 0){
        echo "Error while updating the Books table!";
        $conn->rollback();
        $conn->close();
        http_response_code(500);
        exit;
    }
}

$conn->commit();
$conn->close();

#echo "Successfully updated Books!";

http_response_code(200);
exit;

# redirect to called url
# echo "<script>setTimeout(\"location.href = 'http://ivm108.informatik.htw-dresden.de/ewa/g08/frontend/index.php';\",1500);</script>";


?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestellung Erfolgreich</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #2196F3;
            border: 2px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: auto;
        }

    </style>
</head>
<body>

    <form>
        <h2>Bestellung war Erfolgreich!</h2>
        <p>Ihre Bestellung ist bei uns eingegangen, Sie werden in naher Zukunft </p>
        <p>Kontaktieren Sie unseren Kunden-Support, falls Sie Fragen oder Schwierigkeiten haben.</p>

        <img src="./cancel.png" alt="Canceld order">
    </form>

</body>
</html>