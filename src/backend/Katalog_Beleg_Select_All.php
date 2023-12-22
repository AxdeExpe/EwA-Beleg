<?php

header('Access-Control-Allow-Origin: *');

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

#echo "Successfully connected to DB!";

$statement = $conn->prepare("SELECT * FROM Books");

if(!$statement){
    http_response_code(500);
    exit;
}

$statement->execute();
$result = $statement->get_result();

if ($result->num_rows === 0) {
    $conn->close();
    echo "No books found in DB!";
    http_response_code(404);
    exit;
}

$statement->close();

if ($result) {
    $i = 0;
    $packetJSON = [];

   while ($row = $result->fetch_assoc()) {
        $json = [
            'id' => $row['id'], # primary key
            'image' => base64_encode(file_get_contents($row['image'])),
            'title' => $row['title'],
            'author' => $row['author'],
            'publisher' => $row['publisher'], # publishing company
            'description' => $row['description'],
            'weight' => $row['weight'],
            'price_brutto' => $row['price_brutto'],
            'stock' => $row['stock']
        ];

        $packetJSON[] = $json;
        $i++;
    }

    header('Content-Type: application/json');
    $conn->close();
    #echo "Successfully fetched " . $i . " books from DB!";
    http_response_code(200);
    echo json_encode($packetJSON);
    } 
    else {
        $conn->close();
        http_response_code(500);
        echo "Error: " . $statement . "<br>" . $conn->error;
    }

?>
