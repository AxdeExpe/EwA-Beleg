CREATE TABLE Orders (
    book_id INT NOT NULL,
    order_id INT NOT NULL,
    order_date TIMESTAMP NOT NULL,
    amount INT NOT NULL,
    price DECIMAL NOT NULL,

    modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    stripe_checkout_session_id VARCHAR(100) DEFAULT NULL,
    txn_id VARCHAR(50) NOT NULL,
    customer_name VARCHAR(50) NOT NULL,
    customer_email VARCHAR(50) NOT NULL;
    
    PRIMARY KEY(order_id),
    FOREIGN KEY(book_id) REFERENCES Books(id)
);
