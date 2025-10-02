CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL
);

CREATE TABLE drinks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT,
  image VARCHAR(255)
);

CREATE TABLE payment_methods (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description VARCHAR(255)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  status VARCHAR(50) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  payment_method_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id)
);

CREATE TABLE order_drinks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  drink_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (drink_id) REFERENCES drinks(id)
);

CREATE TABLE inventories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  drink_id INT NOT NULL,
  quantity INT NOT NULL,
  updated_at DATETIME NOT NULL,
  FOREIGN KEY (drink_id) REFERENCES drinks(id)
);