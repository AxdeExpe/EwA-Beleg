DELIMITER //
CREATE TRIGGER after_update_orders
AFTER UPDATE ON Orders
FOR EACH ROW
BEGIN
    UPDATE Books
    SET stock = stock + (OLD.amount - NEW.amount)
    WHERE id = OLD.book_id;
END;
//
DELIMITER ;