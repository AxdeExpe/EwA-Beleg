INSERT INTO Orders (book_id, order_id, order_date, amount, price_)
SELECT
    b.id AS book_id,
    ? AS order_id,
    NOW() AS order_date,
    ? AS amount,
    b.price_brutto * ? AS price
FROM Books b
WHERE b.id = 1;