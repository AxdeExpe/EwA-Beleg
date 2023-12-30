<?php

header('Access-Control-Allow-Origin: *');


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    # no post request
    http_response_code(400);
    exit;
}

if (!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
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



# Datapacket: JSON: {Purchase: [{book_id, title, order_id, order_date, order_date, amount, price}, ...], Stock: [{book_id, title, author, description, publisher, price_netto, weight, stock}, ...]}

# Orders

$statement = $conn->prepare("SELECT o.order_id, o.order_date, b.id, b.title, o.amount, o.price, o.stripe_checkout_session_id, o.txn_id, o.customer_name, o.customer_email, o.user_username FROM Orders o JOIN Books b ON o.book_id = b.id;");

if(!$statement){
    http_response_code(500);
    exit;
}

$statement->execute();
$result = $statement->get_result();

if ($result->num_rows === 0) {
    $conn->close();
    http_response_code(404);
    exit;
}

$statement->close();



if ($result) {
    $purchaseJSON = [];

    while ($row = $result->fetch_assoc()) {
        $json = [
            'id' => $row['id'],
            'title' => $row['title'],
            'order_id' => $row['order_id'],
            'order_date' => $row['order_date'],
            'amount' => $row['amount'],
            'price' => $row['price'],
            'stripe_checkout_session_id' => $row['stripe_checkout_session_id'],
            'txn_id' => $row['txn_id'],
            'customer_name' => $row['customer_name'],
            'customer_email' => $row['customer_email'],
            'username' => $row['user_username']
        ];

        $purchaseJSON[] = $json;
    }

} else {
    $conn->close();
    http_response_code(500);
    exit;
}



# Stock

$statement = $conn->prepare("SELECT * FROM Books");
$statement->execute();
$result = $statement->get_result();

$statement->close();

if($result){

    $stockJSON= [];
    while($row = $result->fetch_assoc()){
        $json = [
            'id' => $row['id'],
            'image' => $row['image'],
            'title' => $row['title'],
            'author' => $row['author'],
            'description' => $row['description'],
            'publisher' => $row['publisher'],
            'price_netto' => $row['price_netto'],
            'mwst' => $row['mwst'],
            'price_brutto' => $row['price_brutto'],
            'weight' => $row['weight'],
            'stock' => $row['stock']
        ];

        $stockJSON[] = $json;
    }

    $conn->close();

    header('Content-Type: application/json');
    $packetJSON = ['Orders' => $purchaseJSON, 'Stock' => $stockJSON];

    echo json_encode($packetJSON);

}
else{
    $conn->close();
    http_response_code(500);
    exit;
}

?>