CREATE TABLE Orders (
    book_id INT NOT NULL,
    order_id INT NOT NULL,
    order_date TIMESTAMP NOT NULL,
    amount INT NOT NULL,
    price DECIMAL NOT NULL,
    
    PRIMARY KEY(order_id),
    FOREIGN KEY(book_id) REFERENCES Books(id)
);
