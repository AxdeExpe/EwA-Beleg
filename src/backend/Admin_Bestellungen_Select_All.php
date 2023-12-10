<?php

$host = "localhost";
$username = "g08";
$password = "em28rust";
$database = "g08";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

#echo "Successfully connected to DB!";

# Datapacket: JSON: {Purchase: [{book_id, title, order_id, order_date, order_date, amount, price}, ...], Stock: [{book_id, title, author, description, publisher, price_netto, weight, stock}, ...]}

# Orders

$statement = $conn->prepare("SELECT o.order_id, o.order_date, b.id, b.title, o.amount, o.price FROM Orders o JOIN Books b ON o.book_id = b.id;");
$statement->execute();
$result = $statement->get_result();

$statement->close();



if ($result) {
    $purchaseJSON = [];

    while ($row = $result->fetch_assoc()) {
        $json = [
            'book_id' => $row['b.id'],
            'title' => $row['b.title'],
            'order_id' => $row['o.order_id'],
            'order_date' => $row['o.order_date'],
            'amount' => $row['amount'],
            'price' => $row['price']
        ];

        $purchaseJSON[] = $json;
    }

} else {
    $conn->close();
    echo "Error: " . $statement . "<br>" . $conn->error;
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
            'book_id' => $row['id'],
            'title' => $row['title'],
            'author' => $row['author'],
            'description' => $row['description'],
            'publisher' => $row['publisher'],
            'price_netto' => $row['price_netto'],
            'weight' => $row['weight'],
            'stock' => $row['stock']
        ];

        $stockJSON[] = $json;
    }

    $conn->close();

    header('Content-Type: application/json');
    $packetJSON = ['Purchase' => $purchaseJSON, 'Stock' => $stockJSON];

    echo json_encode($packetJSON);

}
else{
    $conn->close();
    echo "Error: " . $statement . "<br>" . $conn->error; 
}

?>