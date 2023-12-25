<?php

# database stuff
$host = "localhost";
$username = "g08";
$password = "em28rust";
$database = "g08";



header('Access-Control-Allow-Origin: *');

if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
    # no post request
    http_response_code(400);
    exit;
}


## JSON-Format:
##[
##    {"username": "test_user", "password": "test_password"},
##    {"id": 1, "amount": 2},
##    {"id": 2, "amount": 7}
##]

$requestData = file_get_contents('php://input');

$jsonData = json_decode($requestData, true);

echo $requestData;
echo $jsonData[0]['id'];

if ($jsonData === null || json_last_error() !== JSON_ERROR_NONE) {
    echo 'No valid JSON data found in request.';
    http_response_code(400);
    exit;
}

if (!is_array($jsonData) || count($jsonData) < 0 || !is_array($jsonData[0])) {
    echo 'No valid JSON Array found in request.';
    http_response_code(400);
    exit;
}

$numberOfArrays = count($jsonData) - 1;


# check if all keys of each array has legit values
if($numberOfArrays < 0){
    echo 'No valid JSON Array found in request.';
    http_response_code(400);
    exit;
}





# login part
# check if user is no admin
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
if (!isset($json_data['is_admin']) || $json_data['is_admin'] === '1') {
    echo "Admin can not order anything.";
    http_response_code(401);
    exit;
}






# check if all keys of each array has legit values

for($i = 0; $i < $numberOfArrays; $i++){
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
}






# select all needed books from database

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

$packetJSON = [];

for($i = 0; i < $numberOfArrays; $i++){
    $statement = $conn->prepare("SELECT * FROM Books WHERE id = ?");
    $statement->bind_param("i", $jsonData[$i]['id']);
    $statement->execute();
    $result = $statement->get_result();

    $statement->close();

    if($result->num_rows < 0){
        $conn->close();
        http_response_code(404);
        exit;
    }



    $row = $result->fetch_assoc();

    if($row['stock'] < $jsonData[$i]['amount']){
        $conn->close();
        http_response_code(400);
        exit;
    }

    $json = [
        'title' => $row['title'],
        'description' => $row['description'],
        'price_brutto' => $row['price_brutto']
    ];

    if (file_exists($row['image'])) {
        $json['image'] = base64_encode(file_get_contents($row['image']));
    } else {
        $json['image'] = "";
    }

    $packetJSON[] = $json;

}






# put every item into an arry

$items = [];

for($i = 0; $i < $numberOfArrays; $i++){
    $item = [
        'titel' => $packetJSON[$i]['title'],
        'images' => [getenv('BASE_URL') . $packetJSON[$i]['image']], # stripe.php is in backend folder
        'quantity' => $jsonData[$i]['amount'],
        'description' => $packetJSON[$i]['description'],
        'price' => $packetJSON[$i]['price_brutto'],
        'currency' => 'eur',
    ];

    $items[] = $item;
}




# stripe part

$pk = "pk_test_51OREORC36J02THDS7v1pjY6BICfVf7OgXq8V7fvZhPSd8iIa9A9Zp3NePwm2uCvl3p6dcyLe1UgbB91ItWeoysjv00mRLr04dx";
$sk = "sk_test_51OREORC36J02THDSJqRzCyfAOimB3RTMMOb5j6126e3Yx69FDre0gbMkHz04Ak4Kb3XjIY9sWGdbju60MOVck9WZ00IVbnW19S";



if(isset($_POST['live']) && $_POST['live'] == '1') {
    //if(false) {
    // Secret Key des Grosshändlers - bitte so lassen !!!
    \Stripe\Stripe::setApiKey('sk_test_cFnCai0Ye9NM8Tn9CMo6k0fn00P0R9pt9u');

	$pk="pk_test_aLcPqdtG2FDzxPWu5N9OBNOs00Yt0nKnhS";  //  PK Großhändler - So lassen !!!!
} else {
      // Der Key Ihres eigenen Stripe-Accounts - bitte hier einsetzen->  der nachfolgende Code ist nicht mehr gültig !!!
    \Stripe\Stripe::setApiKey($sk);
}


try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $items, # could be instead [$items]
        'mode' => 'payment',
        'success_url' => 'http://ivm108.informatik.htw-dresden.de/ewa/Demos/bookstore-stripe-checkout/' . 'success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://ivm108.informatik.htw-dresden.de/ewa/Demos/bookstore-stripe-checkout/' . 'cancel.php',
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error in Session::create()";
}


# reference to checkout site
header("Location: " . $session->url);


?>