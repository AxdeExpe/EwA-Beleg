<?php


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        # no post request
        echo "no post request";
        http_response_code(400);
        exit;
    }

    if (!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
        # bad request, no username or password
        echo "bad request, no username or password";
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


    if(!isset($_POST['order_id']) || empty($_POST['order_id']) || !isset($_POST['amount']) || !isset($_POST['price']) || !isset($_POST['order_date'])){
        # bad request, no book_id or order_id or title or amount or price or order_date
        echo "bad request, no book_id or order_id or title or amount or price or order_date";
        http_response_code(400);
        exit;
    }

    if((!ctype_digit($_POST['order_id']) || $_POST['order_id'] < 0 || $_POST['order_id'] > 9999999999)){
        # bad request, order_id is not numeric
        echo "order_id is no int or negativ or too large";
        http_response_code(400);
        exit;
    }

    # check if amount is valid, int (could be negative)
    if(!is_numeric($_POST['amount']) && !empty($_POST['amount'])){
        http_response_code(400);
        exit;
    }

    if(floatval($_POST['amount']) < 0){
        echo "the stock is smaller than 0";
        http_response_code(400);
        exit;
    }

    # check if price_netto is valid
    if (!is_numeric($_POST['price']) && !empty($_POST['price'])) {
        echo "The price is not a valid decimal or float.";
        http_response_code(400);
        exit;
    }

    # check if price is not negative
    if(floatval($_POST['price']) < 0){
        echo "The price is negative.";
        http_response_code(400);
        exit;
    }

    # check if order_date is valid
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $_POST['order_date']);
    $errors = DateTime::getLastErrors();
    
    if (!$date || $errors['warning_count'] !== 0 || $errors['error_count'] !== 0) {
        echo "Das Datum ist ungültig.";
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



    $selectQuery = "SELECT * FROM orders WHERE order_id = ?";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param("i", $_POST['order_id']);
    $selectStmt->execute();
    $result = $selectStmt->get_result();
    $existingData = $result->fetch_assoc();
    $selectStmt->close();

    if (
        (empty($_POST['amount']) || $existingData['amount'] == $_POST['amount']) &&
        (empty($_POST['price']) || $existingData['price'] == $_POST['price']) &&
        (empty($_POST['order_date']) || $existingData['order_date'] == $_POST['order_date'])
    ) {
        echo "Data is identical, no update needed";
        http_response_code(304);
        exit;
    }


    $sql = "UPDATE orders SET
    price = IF(?, IF(LENGTH(?) > 0, ?, price), price),
    amount = IF(?, IF(LENGTH(?) > 0, ?, amount), amount),
    order_date = IF(LENGTH(?) > 0, ?, order_date)
    WHERE order_id = ?";

    $stmt = $conn->prepare($sql);

    if(!$stmt){
    echo "Error while preparing the statement!";
    http_response_code(500);
    exit;
    }

    $stmt->bind_param("dddiiisssi", 
    $_POST['price'], $_POST['price'], $_POST['price'],
    $_POST['amount'], $_POST['amount'], $_POST['amount'],
    $_POST['order_date'], $_POST['order_date'], $_POST['order_date'],
    $_POST['order_id']);


    $stmt->execute();

    $conn->close();

    echo $stmt->get_result();

    echo $stmt->error;

    echo $_POST['image'] . " " . $_POST['title'] . " " . $_POST['author'] . " " . $_POST['price_netto'] . " " . $_POST['mwst'] . " " . $_POST['weight'] . " " . $_POST['stock'] . " " . $_POST['description'] . " " . $_POST['publisher'] . " " . $_POST['id'];

    if ($stmt->affected_rows > 0) {
        echo "successfull updated";
        $stmt->close();
        http_response_code(200);
        exit;
    } 
    else {
        echo "Something went wrong!";
        $stmt->close();
        http_response_code(500);
        exit;
    }

?>