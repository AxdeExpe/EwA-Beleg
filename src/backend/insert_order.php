<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['id']) || !isset($_POST['amount']) || empty($_POST['id']) || empty($_POST['amount'])) {
        # bad request, no username or password
        http_response_code(400);
        exit;
    }

    $book_id = $_POST['id']; 
    $amount = $_POST['amount'];

    $host = "localhost";
    $username = "g08";
    $password = "em28rust";
    $database = "g08";

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        http_response_code(500);
        exit;
    }

    $sql = "INSERT INTO Orders (book_id, order_date, amount, price)
            SELECT
                b.id AS book_id,
                NOW() AS order_date,
                ? AS amount,
                b.price_brutto * ? AS price
            FROM Books b
            WHERE b.id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("iii", $amount, $amount, $book_id);

    // Ausführen der vorbereiteten Anweisung
    $stmt->execute();

    // Überprüfen auf Erfolg
    if ($stmt->affected_rows > 0) {
        $stmt->close();
        $mysqli->close();
        
        http_response_code(200);
        exit;
    } 
    else {
        $stmt->close();
        $mysqli->close();

        http_response_code(500);
        exit;
    }

}
else {
    # no post request
    http_response_code(400);
}

?>