<?php
header('Access-Control-Allow-Origin: *');

require('./stripe-php-master/init.php');

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    # no post request
    http_response_code(400);
    exit;
}

## JSON-Format:
##[
##    {"username": "test_user", "password": "test"},
##    {"id": 1, "amount": 2},
##    {"id": 2, "amount": 7}
##]

$requestData = file_get_contents('php://input');

$jsonData = json_decode($requestData, true);

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

$packetJSON = [];

for($j = 1; $j <= $numberOfArrays; $j++){
    $statement = $conn->prepare("SELECT * FROM Books WHERE id = ?");
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

    $json = [
        'title' => $row['title'],
        'description' => $row['description'],
        'price_brutto' => $row['price_brutto']
    ];

    $packetJSON[] = $json;
}

$conn->close();


#echo json_encode($packetJSON);

# put every item into an seperate arry

$lineItems = [];

for ($i = 0; $i < $numberOfArrays; $i++) {
    $item = [
        'price_data' => [
            'currency' => 'eur',
            'product_data' => [
                'name' => $packetJSON[$i]['title'],
                'description' => $packetJSON[$i]['description']
            ],
            'unit_amount' => round($packetJSON[$i]['price_brutto'] * 100), // in cents
        ],
        'quantity' => $jsonData[$i + 1]['amount'],
    ];

    $lineItems[] = $item;
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

$session = null;

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => 'http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/success.php?session_id={CHECKOUT_SESSION_ID}&data=' . urlencode($requestData),
        'cancel_url' => 'http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/cancel.php',
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Error in Session::create() " . $e->getMessage();
    http_response_code(500);
    exit;
}


# reference to checkout site
header("Location: " . $session->url);

http_response_code(200);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <div class="text">
        <h1>Sie werden zur Bezahlung weitergeleitet!</h1>
    </div>

    <div class="lds-ripple">
        <div>

        </div>
        <div>

        </div>
    </div>

</body>
</html>

<style>

body{
    background-color: blue;
}

.text {
    position: absolute; /* Hinzugefügt */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Zentriert das Element */
    font-family: Arial, Helvetica, sans-serif;
}

.lds-ripple {
  position: relative; /* Hinzugefügt */
  top: 50%;
  left: 50%;
  width: 80px;
  height: 80px;
}
.lds-ripple div {
  position: absolute;
  border: 4px solid #fff;
  opacity: 1;
  border-radius: 50%;
  animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
.lds-ripple div:nth-child(2) {
  animation-delay: -0.5s;
}
@keyframes lds-ripple {
  0% {
    top: 36px;
    left: 36px;
    width: 0;
    height: 0;
    opacity: 0;
  }
  4.9% {
    top: 36px;
    left: 36px;
    width: 0;
    height: 0;
    opacity: 0;
  }
  5% {
    top: 36px;
    left: 36px;
    width: 0;
    height: 0;
    opacity: 1;
  }
  100% {
    top: 0px;
    left: 0px;
    width: 72px;
    height: 72px;
    opacity: 0;
  }
}


</style>