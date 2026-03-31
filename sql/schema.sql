CREATE DATABASE studentdb;

CREATE USER 'lampuser'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON studentdb.* TO 'lampuser'@'localhost';
FLUSH PRIVILEGES;

USE studentdb;

CREATE TABLE students (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    course     VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
