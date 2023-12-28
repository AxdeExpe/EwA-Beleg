<?php

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


echo $_GET['session_id'];
echo $_GET['data'];


$jsonData = json_decode($_GET['data'], true);


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
    http_response_code(401);
    exit;
}



# check if all keys of each array has legit values

for($i = 1; $i <= $numberOfArrays; $i++){
    if(!isset($jsonData[$i]['id']) || !isset($jsonData[$i]['amount']) || empty($jsonData[$i]['id']) || empty($jsonData[$i]['amount'])){
        # bad request, no id or amount
        echo $jsonData[$i]->id;
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



exit;


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



// Set API key 
$stripe = new \Stripe\StripeClient(STRIPE_API_KEY); 
         
// Fetch the Checkout Session to display the JSON result on the success page 
$checkout_session = null;
$api_error = null;

try { 
    $checkout_session = $stripe->checkout->sessions->retrieve($session_id); 
} catch(Exception $e) {  
    $api_error = $e->getMessage();  
    echo $api_error;
    http_response_code(500);
    exit;
} 

if($api_error !== null || $checkout_session === null){
    echo "Invalid transaction!";
    http_response_code(500);
    exit;
}

$customer_details = $checkout_session->customer_details;
$paymentIntent = null;

try { 
    $paymentIntent = $stripe->paymentIntents->retrieve($checkout_session->payment_intent); 
} catch (\Stripe\Exception\ApiErrorException $e) { 
    $api_error = $e->getMessage(); 
    echo $api_error;
    http_response_code(500);
    exit;
}

if($api_error !== null || $paymentIntent === null){
    echo "Unable to fetch the transaction details!";
    http_response_code(500);
    exit;
}

if($paymentIntent === null || $paymentIntent->status !== 'succeeded'){
    echo "Transaction has been failed!";
    http_response_code(500);
    exit;
}

$transactionID = $paymentIntent->id; 
$paidAmount = $paymentIntent->amount; 
$paidAmount = ($paidAmount/100); 
$paidCurrency = $paymentIntent->currency; 
$payment_status = $paymentIntent->status; 
                     
// Customer info 
$customer_name = $customer_email = ''; 

if(!empty($customer_details)){ 
    $customer_name = !empty($customer_details->name) ? $customer_details->name:''; 
    $customer_email = !empty($customer_details->email) ? $customer_details->email:''; 
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
$prevRow = $result->fetch_assoc()

$statement->close();

# insert into the purchases into Orders
$conn->begin_transaction();

for($i = 1; i <= $numberOfArrays; $i++){

    # need to be changed ##########################################################################################################################################################################################################
    // Insert transaction data into the database 
    $sql = "INSERT INTO Orders (customer_name,customer_email,item_name,item_number,item_price,item_price_currency,paid_amount,paid_amount_currency,txn_id,payment_status,stripe_checkout_session_id,created,modified) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())"; 
    $stmt = $db->prepare($sql); 
        
    if(!$stmt){
        http_response_code(500);
        exit;
    }
        
    $stmt->bind_param("ssssdsdssss", $customer_name, $customer_email, $productName, $productID, $productPrice, $currency, $paidAmount, $paidCurrency, $transactionID, $payment_status, $session_id); 
    $stmt->execute(); 
        
    if($stmt->affected_rows <= 0){
        $conn->rollback();
        $conn->close();
        http_response_code(500);
        exit;
    }

    $statement->close();
}

$conn->commit();

for($i = 1; i <= $numberOfArrays; $i++){

    // Führe die Aktualisierung der Books-Tabelle aus
    $updateSql = "UPDATE Books SET stock = stock - ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);

    if (!$updateStmt) {
        http_response_code(500);
        exit;
    }

    $updateStmt->bind_param("ii", $jsonData[$i]['amount'], $json_data[$i]['id']);
    $updateStmt->execute();

    if($updateStmt->affected_rows <= 0){
        $conn->rollback();
        $conn->close();
        http_response_code(500);
        exit;
    }
}

$conn->commit();
$conmn->close();

http_response_code(200);
exit;

# redirect to called url
# echo "<script>setTimeout(\"location.href = 'http://ivm108.informatik.htw-dresden.de/ewa/g08/frontend/index.php';\",1500);</script>";



/*
for($i = 1; i <= $numberOfArrays; $i++){

    $sql = "INSERT INTO Orders (book_id, order_date, amount, price)
        SELECT
            b.id AS book_id,
            NOW() AS order_date,
            ? AS amount,
            b.price_brutto * ? AS price
        FROM Books b
        WHERE b.id = ?";

    $statement = $conn->prepare($sql);

    if(!$statement){
        http_response_code(500);
        exit;
    }

    $statement->bind_param("iii", $jsonData[$i]['amount'], $jsonData[$i]['amount'], $jsonData[$i]['id']);

    $statement->execute();

    if($statement->affected_rows <= 0){
        $conn->rollback();
        $conn->close();
        http_response_code(500);
        exit;
    }

    $statement->close();
}

$conn->commit();

*/




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Die Zahlung ist eingegangen!</h1>
    <h2>Vielen Dank für Ihre Bestellung!</h2>
    <h3>Sie werden in Kürze weitergeleitet!</h3>
</body>
</html>