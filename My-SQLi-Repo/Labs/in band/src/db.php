<?php

// khai báo thông tin kết nối
// các thông tin này phải khớp hoàn toàn với docker-compose.yml
$nameservice = "db-sqli";
$username = "user";
$password= "123456";
$dbname = "sqli_db";

// tạo kết nối , cần cả nameservice bởi vì đây là đang kết nối 2 container lại với nhau 
// mà mỗi container có chức năng riêng biệt thì được tạo ra từ service riêng biệt nên phải khai báo tên service nữa
$conn = new mysqli($nameservice, $username, $password, $dbname);

if ($conn->connect_error) {
    die("connect failded". $conn->connect_error);
}


// bản chất là 2 cái container web và của db nó là 2 cái riêng biệt 
// cần cái file này để kết nối web với db lại với nhau , giả sử bạn nhập vào username = hacker ở web thì ở db nó cũng sẽ nhận được tương tự


?>