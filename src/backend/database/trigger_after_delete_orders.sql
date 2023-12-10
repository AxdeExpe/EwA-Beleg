DELIMITER //
CREATE TRIGGER after_delete_orders
AFTER DELETE ON Orders
FOR EACH ROW
BEGIN
    UPDATE Books
    SET stock = stock + OLD.amount
    WHERE id = OLD.book_id;
END;
//
DELIMITER ;
