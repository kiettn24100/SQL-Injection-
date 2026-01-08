CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username varchar(50) unique,
    password varchar(50)
);

INSERT INTO users (username , password) VALUES ('admin', 'flag{boolean_sql_injection}');
INSERT INTO users (username , password) VALUES ('user01' , '123456');