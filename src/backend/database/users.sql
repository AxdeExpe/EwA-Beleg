CREATE TABLE Users(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    pw VARCHAR(255) NOT NULL,
    UNIQUE KEY unique_benutzer (username, pw)
);