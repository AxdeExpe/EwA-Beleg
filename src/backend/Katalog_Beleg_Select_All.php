<?php

$host = "localhost";
$username = "g08";
$password = "em28rust";
$database = "g08";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Successfully connected to DB!";

$statement = $conn->prepare("SELECT * FROM buecher");
$statement->execute();
$result = $statement->get_result();

$statement->close();

if ($result) {
    $i = 0;
    $packetJSON = [];

    while ($row = $result->fetch_assoc()) {
        $json = [
            'image' => base64_encode(file_get_contents($row['image'])),
            'id' => $row['id'],
            'title' => $row['titel'],
            'author' => $row['author'],
            'publisher' => $row['publisher'], # Verlag
            'description' => $row['description'],
            'amount' => $row['amount'],
            'price' => $row['price'],
            'mwst' => $row['mwst'], # Mehrwertsteuer
            'weight' => $row['weight']
        ];

        $packetJSON[] = $json;
        $i++;
    }

    header('Content-Type: application/json');
    $conn->close();
    echo json_encode($packetJSON);
} else {
    $conn->close();
    echo "Error: " . $statement . "<br>" . $conn->error;
}

?>
