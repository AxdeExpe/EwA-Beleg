CREATE TABLE IF NOT EXISTS Books_Log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    action VARCHAR(10) NOT NULL, -- 'INSERT', 'UPDATE', oder 'DELETE'
    changed_by INT, -- Benutzer-ID, der die Änderung durchgeführt hat
    change_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    image VARCHAR(512),
    title VARCHAR(255),
    author VARCHAR(255),
    description VARCHAR(4096),
    publisher VARCHAR(255),
    mwst DECIMAL,
    price_netto DECIMAL,
    price_brutto DECIMAL,
    weight INT,
    stock INT
);