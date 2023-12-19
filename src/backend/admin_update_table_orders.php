<?php

    header('Access-Control-Allow-Origin: *');


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
        echo "bad request, no order_id or title or amount or price or order_date";
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
    $orderDate = $_POST['order_date'];
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $orderDate);
    
    if ((!$date || $date->format('Y-m-d H:i:s') !== $orderDate) && !empty($_POST['order_date'])) {
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

    if(!$result){
        echo "Error while selecting the data!";
        http_response_code(500);
        exit;
    }

    if($result->num_rows === 0){
        echo "No data found!";
        http_response_code(404);
        exit;
    }

    $existingData = $result->fetch_assoc();
    $selectStmt->close();

    echo intval($_POST['amount']);

    if (
        ($_POST['amount'] == 0 && !empty($_POST['amount'])) ||
        ($_POST['amount'] != 0 && (empty($_POST['amount']) || $existingData['amount'] == $_POST['amount'])) &&
        (empty($_POST['price']) || $existingData['price'] == $_POST['price']) &&
        (empty($_POST['order_date']) || $existingData['order_date'] == $_POST['order_date'])
    ) {
        echo "Data is identical, no update needed";
        http_response_code(304);
        exit;
    }
    
    # delete if amount is 0
    if ($_POST['amount'] == 0) {
        $sql = "DELETE FROM orders WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_POST['order_id']);
        $stmt->execute();
        $conn->close();
        echo "Successfully deleted";
        http_response_code(200);
        exit;
    }
    

    if(empty($_POST['amount'])){
        $selectAmount = "SELECT amount FROM orders WHERE order_id = ?";
        $selectAmountStmt = $conn->prepare($selectAmount);
        $selectAmountStmt->bind_param("i", $_POST['order_id']);

        if(!$selectAmountStmt){
            echo "Error while preparing the statement!";
            http_response_code(500);
            exit;
        }

        $selectAmountStmt->execute();
        $result = $selectAmountStmt->get_result();

        if(!$result){
            echo "Error while selecting the data!";
            http_response_code(500);
            exit;
        }

        $amount = $result->fetch_assoc()['amount'];
        $selectAmountStmt->close();

        $_POST['amount'] = $amount;
    }


    # get the price of the book
    $selectBookID = "SELECT book_id, amount FROM orders WHERE order_id = ?";
    $selectBookIDStmt = $conn->prepare($selectBookID);

    if(!$selectBookIDStmt){
        echo "Error while preparing the statement!";
        http_response_code(500);
        exit;
    }

    $selectBookIDStmt->bind_param("i", $_POST['order_id']);
    $selectBookIDStmt->execute();

    $result = $selectBookIDStmt->get_result();

    if(!$result){
        echo "Error while selecting the data!";
        http_response_code(500);
        exit;
    }

    $row = $result->fetch_assoc();

    $book_id = $row['book_id'];
    $old_amount = $row['amount'];
    $selectBookIDStmt->close();

    $selectPrice = "SELECT price_brutto, stock FROM books WHERE id = ?";
    $selectPriceStmt = $conn->prepare($selectPrice);

    if(!$selectPriceStmt){
        echo "Error while preparing the statement!";
        http_response_code(500);
        exit;
    }

    $selectPriceStmt->bind_param("i", $book_id);
    $selectPriceStmt->execute();

    $result = $selectPriceStmt->get_result();

    if(!$result){
        echo "Error while selecting the data!";
        http_response_code(500);
        exit;
    }

    $row = $result->fetch_assoc();
    $price_brutto = $row['price_brutto'];
    $stock = $row['stock'];

    $selectPriceStmt->close();


    # 1 >= 1 + (9 - 19)
    if(($stock + intval($old_amount)) < intval($_POST['amount'])){
        echo "The stock is smaller than the amount";
        http_response_code(400);
        exit;
    }


    # update the order

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

    $priceCalc = floatval($_POST['amount']) * $price_brutto;


    echo $priceCalc . " " . $price_brutto . " " . $_POST['amount'];

    if(empty($_POST['price'])){
        $stmt->bind_param("dddiiissi", 
        $priceCalc, $priceCalc, $priceCalc,
        $_POST['amount'], $_POST['amount'], $_POST['amount'],
        $_POST['order_date'], $_POST['order_date'],
        $_POST['order_id']);
    }
    else{
        $stmt->bind_param("dddiiissi", 
        $_POST['price'], $_POST['price'], $_POST['price'],
        $_POST['amount'], $_POST['amount'], $_POST['amount'],
        $_POST['order_date'], $_POST['order_date'],
        $_POST['order_id']);
    }


    $stmt->execute();

    $conn->close();

    echo $stmt->get_result();

    echo $stmt->error;

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