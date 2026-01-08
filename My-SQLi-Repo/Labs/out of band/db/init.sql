CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) unique NOT NULL,
    password VARCHAR(50) NOT NULL
);

INSERT INTO users (username,password) VALUES ('admin','flag{out_of_band_SQL_injection}');
INSERT INTO users (username,password) VALUES ('user01', 123456);

-- reset các hàm ở trong file plugin kia nếu cần thiết 
DROP FUNCTION IF EXISTS sys_eval;
DROP FUNCTION IF EXISTS sys_exec;
DROP FUNCTION IF EXISTS sys_get;
DROP FUNCTION IF EXISTS sys_set;

-- sau khi đã ánh xạ cái file plugin kia vào trong đường dẫn hệ thống của container thì cái file plugin nó cũng chỉ nằm im lìm ở trong đó  
-- mysql nó chạy nhưng nó vẫn ko có các tính năng trong plugin kia 
-- lúc này cần chạy lệnh CREATE FUNCTION để nạp chương trình vào mysql và để mysql có khả năng sử dụng các tính năng đó
CREATE FUNCTION sys_eval RETURNS STRING SONAME 'lib_mysqludf_sys.so';
CREATE FUNCTION sys_exec RETURNS INT SONAME 'lib_mysqludf_sys.so';
CREATE FUNCTION sys_get RETURNS STRING SONAME 'lib_mysqludf_sys.so';
CREATE FUNCTION sys_set RETURNS INT SONAME 'lib_mysqludf_sys.so';