CREATE TABLE login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL, -- Länge von 45 Zeichen für IPv6-Adressen
    username VARCHAR(50) NOT NULL,
    pw VARCHAR(255) NOT NULL,
    timestamp DATETIME NOT NULL
);
