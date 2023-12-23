CREATE TABLE Users(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,  -- Hinzugefügte E-Mail-Spalte
    pw VARCHAR(255) NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    UNIQUE KEY unique_benutzer (username, pw),
    UNIQUE KEY unique_email (email)  -- Eindeutige Einschränkung für E-Mail-Adressen
);