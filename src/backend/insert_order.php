<?php

    header('Access-Control-Allow-Origin: *');

    if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
        # no post request
        http_response_code(400);
        exit;
    }


    ## JSON-Format:
    ##[
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

    $numberOfArrays = count($jsonData);


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

    exit;



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



    # insert into the purchases into Orders
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
?>