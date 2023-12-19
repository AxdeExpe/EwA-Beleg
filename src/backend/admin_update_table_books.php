<?php

    header('Access-Control-Allow-Origin: *');


    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        # no post request
        echo "no post";
        http_response_code(400);
        exit;
    }

    if(!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])){
        # bad request, no username or password
        echo "username or password are not set!";
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
        echo "no admin";
        http_response_code($http_status);
        exit;
    }




    # user is admin

    # id not changeable, [needed for update]
    # image is changeable
    # title is changeable
    # author is changeable
    # price_netto is changeable
    # mwst is changeable
    # weight is changeable
    # stock is changeable
    # description is changeable
    # publisher is changeable

    # check if all fields are set
    if(!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['image']) || !isset($_POST['title']) || !isset($_POST['author']) || !isset($_POST['price_netto']) || !isset($_POST['mwst']) || !isset($_POST['weight']) || !isset($_POST['stock']) || !isset($_POST['description']) || !isset($_POST['publisher'])){
       # bad request, not all fields set
       echo "Not all fields are set";
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

    #check if image is valid, string
    if((!is_string($_POST['image']) || strlen($_POST['image']) > 512 || is_numeric($_POST['image']))){
        # bad request, image is not string
        echo "image is no string or too long";
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


    $host = "localhost";
    $username = "g08";
    $password = "em28rust";
    $database = "g08";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        http_response_code(500);
        exit;
    }


    $selectQuery = "SELECT * FROM books WHERE id = ?";
    $selectStmt = $conn->prepare($selectQuery);
    $selectStmt->bind_param("i", $_POST['id']);
    $selectStmt->execute();
    $result = $selectStmt->get_result();
    
    if($result->num_rows === 0){
        echo "no book with this id";
        http_response_code(404);
        exit;
    }
    
    $existingData = $result->fetch_assoc();
    $selectStmt->close();

    if (
        (empty($_POST['image']) || $existingData['image'] == $_POST['image']) &&
        (empty($_POST['title']) || $existingData['title'] == $_POST['title']) &&
        (empty($_POST['author']) || $existingData['author'] == $_POST['author']) &&
        (empty($_POST['price_netto']) || $existingData['price_netto'] == $_POST['price_netto']) &&
        (empty($_POST['mwst']) || $existingData['mwst'] == $_POST['mwst']) &&
        (empty($_POST['weight']) || $existingData['weight'] == $_POST['weight']) &&
        (empty($_POST['stock']) || $existingData['stock'] == $_POST['stock']) &&
        (empty($_POST['description']) || $existingData['description'] == $_POST['description']) &&
        (empty($_POST['publisher']) || $existingData['publisher'] == $_POST['publisher'])
    ) {
        echo "Data is identical, no update needed";
        http_response_code(304);
        exit;
    }




    $sql = "UPDATE books SET
            image = IF(LENGTH(?) > 0, ?, image),
            title = IF(LENGTH(?) > 0, ?, title),
            author = IF(LENGTH(?) > 0, ?, author),
            price_netto = IF(?, IF(LENGTH(?) > 0, ?, price_netto), price_netto),
            mwst = IF(?, IF(LENGTH(?) > 0, ?, mwst), mwst),
            weight = IF(?, IF(LENGTH(?) > 0, ?, weight), weight),
            stock = IF(?, IF(LENGTH(?) > 0, ?, stock), stock),
            description = IF(LENGTH(?) > 0, ?, description),
            publisher = IF(LENGTH(?) > 0, ?, publisher)
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if(!$stmt){
        echo "Error while preparing the statement!";
        http_response_code(500);
        exit;
    }

    $stmt->bind_param("ssssssddddddiiiiiissssi", 
    $_POST['image'], $_POST['image'],
    $_POST['title'], $_POST['title'],
    $_POST['author'], $_POST['author'],
    $_POST['price_netto'], $_POST['price_netto'], $_POST['price_netto'],
    $_POST['mwst'], $_POST['mwst'], $_POST['mwst'],
    $_POST['weight'], $_POST['weight'], $_POST['weight'],
    $_POST['stock'], $_POST['stock'], $_POST['stock'],
    $_POST['description'], $_POST['description'],
    $_POST['publisher'], $_POST['publisher'],
    $_POST['id']);

    echo $sql;

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