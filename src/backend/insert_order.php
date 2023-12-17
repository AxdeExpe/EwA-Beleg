<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['id']) || !isset($_POST['amount']) || empty($_POST['id']) || empty($_POST['amount'])) {
        # bad request, no id or amount
        http_response_code(400);
        exit;
    }

    if((!ctype_digit($_POST['id']) || $_POST['id'] < 0 || $_POST['id'] > 9999999999)){
        # bad request, book_id is not numeric
        http_response_code(400);
        exit;
    }

    if((!ctype_digit($_POST['amount']) || $_POST['amount'] < 0 || $_POST['amount'] > 9999999999)){
        # bad request, amount is not numeric
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


    $conn->begin_transaction();

    $sql = "INSERT INTO Orders (book_id, order_date, amount, price)
            SELECT
                b.id AS book_id,
                NOW() AS order_date,
                ? AS amount,
                b.price_brutto * ? AS price
            FROM Books b
            WHERE b.id = ?";

    try {
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Fehler beim Vorbereiten der SQL-Anweisung: " . $conn->error);
        }

        $stmt->bind_param("iii", $amount, $amount, $book_id);

        // Ausführen der vorbereiteten Anweisung
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $stmt->close();
        
            // Commit die Transaktion
            $conn->commit();
        
            // Führe die Aktualisierung der Books-Tabelle aus
            $updateSql = "UPDATE Books SET stock = stock - ? WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
        
            if (!$updateStmt) {
                throw new Exception("Fehler beim Vorbereiten der UPDATE-Anweisung: " . $conn->error);
            }
        
            $updateStmt->bind_param("ii", $amount, $book_id);
            $updateStmt->execute();
        
            if ($updateStmt->affected_rows > 0) {
                // Überprüfe, ob der Lagerbestand nicht negativ ist
                $checkStockSql = "SELECT stock FROM Books WHERE id = ?";
                $checkStockStmt = $conn->prepare($checkStockSql);
        
                if (!$checkStockStmt) {
                    throw new Exception("Fehler beim Vorbereiten der Überprüfungsabfrage: " . $conn->error);
                }
        
                $checkStockStmt->bind_param("i", $book_id);
                $checkStockStmt->execute();
                $checkStockStmt->bind_result($updatedStock);
                $checkStockStmt->fetch();
                $checkStockStmt->close();
        
                if ($updatedStock >= 0) {
                    $updateStmt->close();
                    $conn->close();
        
                    http_response_code(200);
                    exit;
                } else {
                    throw new Exception("Der Lagerbestand darf nicht negativ sein.");
                }
            } else {
                throw new Exception("Die UPDATE-Anweisung hat keine Zeilen beeinflusst.");
            }
        } else {
            throw new Exception("Die INSERT-Anweisung hat keine Zeilen beeinflusst.");
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
        // Rollback der Transaktion bei einem Fehler
        $conn->rollback();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        // Rollback der Transaktion bei einem Fehler
        $conn->rollback();
    } finally {
        // Schließe die Verbindung
        if ($stmt) {
            $stmt->close();
        }
        $conn->close();
        
        // Setze den HTTP-Statuscode entsprechend
        http_response_code(500);
        exit;
    }
}
else {
    # no post request
    http_response_code(400);
}

?>