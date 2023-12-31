CREATE TABLE Books (
    id INT NOT NULL AUTO_INCREMENT,
    image VARCHAR(512) NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    description VARCHAR(4096) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    mwst DECIMAL NOT NULL DEFAULT 0.07,
    price_netto DECIMAL NOT NULL,
    price_brutto DECIMAL GENERATED ALWAYS AS (price_netto * (1 + mwst)) STORED,
    weight int NOT NULL,
    stock INT NOT NULL,
    
    PRIMARY KEY(id)
);