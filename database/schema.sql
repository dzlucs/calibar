SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS galleries;
DROP TABLE IF EXISTS cart_items;
DROP TABLE IF EXISTS carts;
DROP TABLE IF EXISTS addresses;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS drinks;
DROP TABLE IF EXISTS categories;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  encrypted_password VARCHAR(255) NOT NULL,
  profile_photo VARCHAR(50)
);

CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255)
);

/* CREATE TABLE drinks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT,
  category_id INT,
  visibility ENUM('visible', 'invisible') NOT NULL,
  admin_id INT NOT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
); */

CREATE TABLE drinks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  admin_id INT NOT NULL,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE CASCADE
);

CREATE TABLE addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  street VARCHAR(255) NOT NULL,
  number INT,
  cep INT,
  neighborhood VARCHAR(255),
  city VARCHAR(255),
  state VARCHAR(255),
  details text,
  name VARCHAR(255),
  FOREIGN KEY (user_id) REFERENCES users(id)
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
  quantity INT,
  unit_price DECIMAL(10,2),
  FOREIGN KEY (cart_id) REFERENCES carts(id),
  FOREIGN KEY (drink_id) REFERENCES drinks(id)
);

/*image_name ao invés de path*/
CREATE TABLE drink_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  drink_id INT NOT NULL,
  path VARCHAR(255),
  FOREIGN KEY (drink_id) REFERENCES drinks(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  payment_method_id INT NOT NULL,
  status VARCHAR(50) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  payment_method ENUM('credito', 'debito', 'pix') NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
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

SET foreign_key_checks = 1;