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
```
CREATE TABLE `roles` (
   `idRole` int NOT NULL AUTO_INCREMENT,
   `nameRole` varchar(45) NOT NULL,
   PRIMARY KEY (`idRole`),
   UNIQUE KEY `idRoles_UNIQUE` (`idRole`),
   UNIQUE KEY `rolescol_UNIQUE` (`nameRole`)
 ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `topics` (
   `idTopic` int NOT NULL AUTO_INCREMENT,
   `nameTopic` varchar(45) NOT NULL,
   `color` varchar(45) NOT NULL DEFAULT '0, 0, 255',
   PRIMARY KEY (`idTopic`),
   UNIQUE KEY `idTopics_UNIQUE` (`idTopic`),
   UNIQUE KEY `topicscol_UNIQUE` (`nameTopic`)
 ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `users` (
   `idUser` int NOT NULL AUTO_INCREMENT,
   `username` varchar(45) NOT NULL,
   `password` varchar(45) NOT NULL,
   `phone` varchar(45) NOT NULL,
   `idRole` int NOT NULL,
   `avatar` varchar(255) NOT NULL DEFAULT 'uploads/images/users/No_Image_Available.jpg',
   `createAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`idUser`),
   UNIQUE KEY `idUsers_UNIQUE` (`idUser`),
   UNIQUE KEY `phone_UNIQUE` (`phone`),
   KEY `FK_users_roles_idx` (`idRole`),
   CONSTRAINT `FK_users_roles` FOREIGN KEY (`idRole`) REFERENCES `roles` (`idRole`)
 ) ENGINE=InnoDB AUTO_INCREMENT=606 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `courses` (
   `idCourse` int NOT NULL AUTO_INCREMENT,
   `idTopic` int DEFAULT NULL,
   `nameCourse` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
   `imgCourse` varchar(500) NOT NULL DEFAULT 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg',
   `descrip` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
   `price` double NOT NULL DEFAULT '0',
   `sale` double DEFAULT NULL,
   `idTeacher` int NOT NULL,
   `createAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`idCourse`),
   UNIQUE KEY `idCourse_UNIQUE` (`idCourse`),
   KEY `FK_courses_topics_idx` (`idTopic`),
   KEY `FK_courses_users_idx` (`idTeacher`),
   CONSTRAINT `FK_courses_topics` FOREIGN KEY (`idTopic`) REFERENCES `topics` (`idTopic`),
   CONSTRAINT `FK_courses_users` FOREIGN KEY (`idTeacher`) REFERENCES `users` (`idUser`)
 ) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `units` (
   `idUnit` int NOT NULL AUTO_INCREMENT,
   `idCourse` int NOT NULL,
   `nameUnit` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
   `order` int NOT NULL,
   `createAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`idUnit`),
   UNIQUE KEY `idUnits_UNIQUE` (`idUnit`),
   KEY `FK_units_courses_idx` (`idCourse`),
   CONSTRAINT `FK_units_courses` FOREIGN KEY (`idCourse`) REFERENCES `courses` (`idCourse`)
 ) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `lessons` (
   `idLesson` int NOT NULL AUTO_INCREMENT,
   `idUnit` int NOT NULL,
   `nameLesson` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
   `descrip` varchar(255) DEFAULT NULL,
   `urlVideo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
   `order` int NOT NULL,
   `createAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`idLesson`),
   UNIQUE KEY `idLessons_UNIQUE` (`idLesson`),
   KEY `FK_lessions_units_idx` (`idUnit`),
   CONSTRAINT `FK_lessions_units` FOREIGN KEY (`idUnit`) REFERENCES `units` (`idUnit`)
 ) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

CREATE TABLE `comments` (
   `idComment` int NOT NULL AUTO_INCREMENT,
   `idLesson` int NOT NULL,
   `idUser` int NOT NULL,
   `Content` longtext NOT NULL,
   `updateAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `CreateAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `parentComment` int DEFAULT NULL,
   PRIMARY KEY (`idComment`),
   UNIQUE KEY `idComment_UNIQUE` (`idComment`),
   KEY `FK_comments_users_idx` (`idUser`),
   KEY `FK_commets_lessons_idx` (`idLesson`),
   KEY `FK_comments_comments_idx` (`parentComment`),
   CONSTRAINT `FK_comments_comments` FOREIGN KEY (`parentComment`) REFERENCES `comments` (`idComment`),
   CONSTRAINT `FK_comments_users` FOREIGN KEY (`idUser`) REFERENCES `users` (`idUser`),
   CONSTRAINT `FK_commets_lessons` FOREIGN KEY (`idLesson`) REFERENCES `lessons` (`idLesson`)
 ) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `ratings` (
   `idRating` int NOT NULL AUTO_INCREMENT,
   `idStudent` int NOT NULL,
   `idCourse` int NOT NULL,
   `rated` double NOT NULL,
   `content` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
   `updateAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`idRating`),
   UNIQUE KEY `idratings_UNIQUE` (`idRating`),
   KEY `FK_ratings_users_idx` (`idStudent`),
   KEY `FK_ratings_courses_idx` (`idCourse`),
   CONSTRAINT `FK_ratings_courses` FOREIGN KEY (`idCourse`) REFERENCES `courses` (`idCourse`),
   CONSTRAINT `FK_ratings_users` FOREIGN KEY (`idStudent`) REFERENCES `users` (`idUser`)
 ) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `registered_courses` (
   `idRegis` int NOT NULL AUTO_INCREMENT,
   `idStudent` int NOT NULL,
   `idCourse` int NOT NULL,
   `costed` double NOT NULL DEFAULT '0',
   `registerAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`idRegis`),
   UNIQUE KEY `idRegis_UNIQUE` (`idRegis`),
   KEY `FK_regisCourses_students_idx` (`idStudent`),
   KEY `FK_regisCourses_courses_idx` (`idCourse`),
   CONSTRAINT `FK_regisCourses_courses` FOREIGN KEY (`idCourse`) REFERENCES `courses` (`idCourse`),
   CONSTRAINT `FK_regisCourses_students` FOREIGN KEY (`idStudent`) REFERENCES `users` (`idUser`)
 ) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci

 CREATE TABLE `progress_learns` (
   `idProgress` int NOT NULL AUTO_INCREMENT,
   `idStudent` int NOT NULL,
   `idLesson` int NOT NULL,
   `state` tinyint NOT NULL DEFAULT '0',
   `updateAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `createAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`idProgress`),
   UNIQUE KEY `idProgresslearns_UNIQUE` (`idProgress`),
   KEY `FK_progress_users_idx` (`idStudent`),
   KEY `FK_progress_lesson_idx` (`idLesson`),
   CONSTRAINT `FK_progress_lesson` FOREIGN KEY (`idLesson`) REFERENCES `lessons` (`idLesson`),
   CONSTRAINT `FK_progress_users` FOREIGN KEY (`idStudent`) REFERENCES `users` (`idUser`)
 ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
```
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