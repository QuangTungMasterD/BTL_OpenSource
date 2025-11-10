<h1 align="center">🎓 Faculty of Information Technology (DaiNam University)</h1>

<h2 align="center">ONLINE COURSE MANAGEMENT</h2>

<p align="center">
  <img src="/aiotlab_logo.png" width="250"/>
  <img src="/fitdnu_logo.png" alt="Dai Nam University" width="250"/>
  <img src="/dnu_logo.png" alt="Dai Nam University" width="250"/>
</p>


</div>
<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/AIoTLab-green?style=for-the-badge" /></a>
  <a href="#"><img src="https://img.shields.io/badge/Faculty%20of%20Information%20Technology-0078D7?style=for-the-badge" /></a>
  <a href="#"><img src="https://img.shields.io/badge/DaiNam%20University-orange?style=for-the-badge" /></a>
</p>

---

## 📘 1. Giới thiệu.

Hệ thống **Quản lý Khóa học Online** được xây dựng nhằm hỗ trợ việc tổ chức, quản lý và học tập trực tuyến một cách hiệu quả.  
Người quản lý có thể thêm, chỉnh sửa và theo dõi các khóa học; giảng viên có thể đăng tải nội dung, bài tập;  
và sinh viên có thể đăng ký, học, nộp bài, làm bài kiểm tra và theo dõi tiến độ học tập của mình.

---

## 🛠️ 2. Các công nghệ được sử dụng.

<h3 align="center">💻 Hệ điều hành</h3>  
<p align="center">
  <img src="https://img.shields.io/badge/MACOS-black?style=for-the-badge&logo=apple&logoColor=white" />
  <img src="https://img.shields.io/badge/WINDOWS-blue?style=for-the-badge&logo=windows&logoColor=white" />
  <img src="https://img.shields.io/badge/UBUNTU-orange?style=for-the-badge&logo=ubuntu&logoColor=white" />
</p>

<h3 align="center">🌐 Công nghệ chính</h3> 
<p align="center">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white" />
  <img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white" />
  <img src="https://img.shields.io/badge/JAVASCRIPT-F7DF1E?style=for-the-badge&logo=javascript&logoColor=white" />
  <img src="https://img.shields.io/badge/TAILWIND%20CSS-38B2AC?style=for-the-badge&logo=tailwindcss&logoColor=white" />
</p>

<h3 align="center">🧠 Web Server & Database</h3>
<p align="center">
  <img src="https://img.shields.io/badge/APACHE-D22128?style=for-the-badge&logo=apache&logoColor=white" />
  <img src="https://img.shields.io/badge/MYSQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white" />
</p>

<h3 align="center">🗄️ Database Management Tools</h3>
<p align="center">
  <img src="https://img.shields.io/badge/MySQL%20Workbench-00758F?style=for-the-badge&logo=mysql&logoColor=white" />
</p>

---
## 🎓 2. Hình ảnh các chức năng.
#### 2.1. Hình ảnh trang dashboard.
<img src="/dashboard-page.png" />

#### 2.2. Hình ảnh trang mô hình trang quản lý.
<img src="/model-manager-page.png" />

#### 2.3. Hình ảnh trang khóa học.
<img src="/course-id.png" />

#### 2.4. Hình ảnh trang cá nhân.
<img src="/user-profile.png" />

---

## ⚙️ 4. Cài đặt.

### 🧰 4.1. Cài đặt công cụ, môi trường và các thư viện cần thiết.

**Tải và cài đặt XAMPP**  
🔗 [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)  
> 💡 *Khuyến nghị sử dụng XAMPP với PHP 8.x.*

**Cài đặt Visual Studio Code** và các extension:
- 🧩 PHP Intelephense  
- 🗄️ MySQL  
- 🎨 Prettier – Code Formatter  

---

### 📂 4.2. Tải project.

Clone project về thư mục `htdocs` của XAMPP (ví dụ ổ C:)

```bash
cd C:\xampp\htdocs
git clone https://github.com/QuangTungMasterD/BTL_OpenSource.git
```
### 4.3. Setup database.

### 4.4. Setup tham số kết nối.
```php
<?php
  function getDbConnection() {
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "khoa_hoc_online";
      $port = 3306;

      $conn = mysqli_connect($servername, $username, $password, $dbname, $port);

      if (!$conn) {
          die("Kết nối database thất bại: " . mysqli_connect_error());
      }
      
      mysqli_set_charset($conn, "utf8");
      return $conn;
  }
?>
```
### 4.5. Chạy hệ thống.
Mở XAMPP Control Panel - Start Apache và MySQL
Truy cập hệ thống <a href="http://localhost/dashboard">http://localhost/dashboard</a>
### 4.6. Đăng nhập lần đầu.
Hệ thống có thể cấp tài khoản admin.
Khi admin đăng nhập hệ thống lần đầu có thể:
- Thêm giáo viên và cấp tài khoản.
- Tạo thông tin các khóa học, chủ đề, ...
- Quản lý các bài học, bình luận đánh giá.