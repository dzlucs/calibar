CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name TEXT NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  encrypted_password VARCHAR(255) NOT NULL,
  profile_photo VARCHAR(50) NOT NULL
);

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  street VARCHAR(255) NOT NULL,
  number INT,
  cep INT,
  neighborhood VARCHAR(255),
  city VARCHAR(255),
  state VARCHAR(255),
  details text,
  name varchar
);

CREATE TABLE carts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE cart_items(
  id INT AUTO_INCREMENT PRIMARY KEY,
  cart_id INT NOT NULL,
  drink_id INT NOT NULL,
  FOREIGN KEY (cart_id) REFERENCES carts(id),
  FOREIGN KEY (drink_id) REFERENCES drinks(id),
  quantity INT,
  unit_price DECIMAL(10,2)

);

CREATE TABLE drinks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT,
  category_id INT,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  visibility ENUM('visible', 'invisible') NOT NULL
);

CREATE TABLE galleries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  drink_id INT NOT NULL,
  FOREIGN KEY (drink_id) REFERENCES drinks(id),
  path VARCHAR(255)
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  payment_method_id INT NOT NULL,
  status VARCHAR(50) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  payment_method ENUM('credito', 'debito', 'pix') NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
);

CREATE TABLE order_items(
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  drink_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (drink_id) REFERENCES drinks(id)
);