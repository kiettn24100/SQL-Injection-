CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_product VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO products (name_product, price, stock) VALUES ('Candy', 2, 1000);
INSERT INTO products (name_product, price, stock) VALUES ('Cake', 3, 2000);
INSERT INTO products (name_product, price, stock) VALUES ('Cookie', 1000, 1);

INSERT INTO users (username, password, email) VALUES ('admin', 'flag{sqli_injection}', 'admin@sql.com');
INSERT INTO users (username, password, email) VALUES ('player01', '123456', 'player01@sql.com');
INSERT INTO users (username, password, email) VALUES ('player02', '123456', 'player02@sql.com');
INSERT INTO users (username, password, email) VALUES ('player03', '123456', 'player03@sql.com');