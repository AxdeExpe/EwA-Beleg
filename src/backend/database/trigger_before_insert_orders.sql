DELIMITER //

CREATE TRIGGER before_insert_orders
BEFORE INSERT ON Orders
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;

    SELECT stock INTO available_stock
    FROM Books
    WHERE id = NEW.book_id;

    IF available_stock < NEW.amount THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Nicht genügend Lagerbestand für Buch';
    END IF;
    
END;

//
DELIMITER ;
