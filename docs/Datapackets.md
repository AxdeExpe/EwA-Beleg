# Login.php
## POST-Request

- **Data:**
  - `username`: needed
  - `password`: needed

- **HTTP-Response:**
  - `500`: Server Error
  - `400`: username or password are empty or not set, no POST-Request
  - `200`: successful logged in 
  - `401`: not authorized

# Register.php
## POST-Request

- **Data:**
  - `username`: needed
  - `password`: needed
  - `email`: needed

- **HTTP-Response:**
  - `500`: Server Error
  - `400`: username or password or email are empty or not set, no POST-Request
  - `200`: successful registered
  - `409`: user already exists

# Katalog_Beleg_Select_All.php
## GET-Request but u can use every other request

- **Data:**
  - Nothing

- **HTTP-Response:**
  - `500`: Server Error
  - `404`: No Data found
  - `200`: Found Data and sent it too

# insert_order.php
## POST-Request

- **Data:**
  - `id`: needed (positive int)
  - `amount`: needed (positive int)

- **HTTP-Responses:**
  - `500`: Server Error
  - `200`: Successfully inserted
  - `400`: No POST-Request

# Admin_Bestellungen_Select_All.php
## POST-Request

- **Data:**
  - `username`: needed
  - `password`: needed

- **HTTP-Responses:**
  - `400`: no username or password, no POST-Request
  - `200`: Found data and send it back
  - `500`: Server Error
  - `404`: nothing found
  - Anything else: from login.php

# Admin_update_table_books.php
## POST-Request

- **Data:** 
  - `username`: needed
  - `password`: needed
  - `id` (`book_id`): not NULL (positive int)
  - `image`: NULL
  - `title`: NULL
  - `author`: NULL
  - `price_netto`: NULL (positive decimal)
  - `mwst`: NULL (positive decimal)
  - `weight`: NULL (positive int)
  - `stock`: NULL (positive int)
  - `description`: NULL
  - `publisher`: NULL

- **HTTP-Responses:**
  - `400`: No POST-Request, the datafields are not set or invalid
  - `404`: no updatable rows found
  - `500`: Server error
  - `304`: no update needed, data is not different
  - `200`: successfully updated
  - Anything else: from login.php

# Admin_update_table_orders.php
## POST-Request

- **Data:**
  - `username`: needed
  - `password`: needed
  - `order_id`: needed (psoitive int)
  - `amount`: NULL (positive int)
  - `price`: NULL (positive decimal)
  - `order_date`: NULL (Format: 2023-12-16 18:51:49)

- **HTTP-Responses:**
  - `200`: successfully updated
  - `500`: Server error
  - `400`: data is invalid, no POST-Request
  - `304`: Data is not different
  - `404`: no updatable rows found
  - Anything else: login.php



# Admin_insert_book.php
## POST-Request

- **Data:**
  - `username`: needed
  - `password`: needed
  - `order_id`: needed (psoitive int)
  - `amount`: NULL (positive int)
  - `price`: NULL (positive decimal)
  - `order_date`: NULL (Format: 2023-12-16 18:51:49)

- **HTTP-Responses:**
  - `200`: successfully updated
  - `500`: Server error
  - `400`: data is invalid, no POST-Request
  - `304`: Data is not different
  - `404`: no updatable rows found
  - Anything else: login.php