DELIMITER //
CREATE TRIGGER books_trigger
AFTER DELETE ON Books
FOR EACH ROW
BEGIN
    INSERT INTO Books_Log (book_id, action, changed_by)
    VALUES (OLD.id, 'DELETE', NULL);
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER books_trigger
AFTER INSERT ON Books
FOR EACH ROW
BEGIN
    INSERT INTO Books_Log (book_id, action, changed_by, image, title, author, description, publisher, mwst, price_netto, price_brutto, weight, stock)
    VALUES (NEW.id, 'INSERT', NULL, NEW.image, NEW.title, NEW.author, NEW.description, NEW.publisher, NEW.mwst, NEW.price_netto, NEW.price_brutto, NEW.weight, NEW.stock);
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER books_trigger
AFTER UPDATE ON Books
FOR EACH ROW
BEGIN
    INSERT INTO Books_Log (book_id, action, changed_by, image, title, author, description, publisher, mwst, price_netto, price_brutto, weight, stock)
    VALUES (NEW.id, 'UPDATE', NULL, NEW.image, NEW.title, NEW.author, NEW.description, NEW.publisher, NEW.mwst, NEW.price_netto, NEW.price_brutto, NEW.weight, NEW.stock);
END;
//
DELIMITER ;