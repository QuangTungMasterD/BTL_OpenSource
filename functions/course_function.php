<?
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/rating_function.php';
require_once __DIR__ . '/registered-courses_function.php';
require_once __DIR__ . '/unit_function.php';

function caculCourseByTopic() {
  $conn = getDbConnection();

  $sql = "SELECT t.nameTopic, COUNT(c.idCourse) AS totalCourses
    FROM topics t
    LEFT JOIN courses c ON t.idTopic = c.idTopic
    GROUP BY t.nameTopic
    ORDER BY totalCourses DESC;";
  
  $result = mysqli_query($conn, $sql);
  $courses = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $courses[] = $row;
    }
  }

  mysqli_close($conn);
  return $courses;
}

function getTotalCourse() {
  $conn = getDbConnection();
  $sql = "SELECT COUNT(*) AS total FROM courses c
    LEFT JOIN topics t ON t.idTopic = c.idTopic
    LEFT JOIN users u ON u.iduser = c.idTeacher
  ";
  $result = mysqli_query($conn, $sql);
  $total = 0;
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $total = $row['total'];
    }
  }

  mysqli_close($conn);
  return $total;
}

function getTotalPageCourse($search = '', $idUser = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM courses c
    LEFT JOIN topics t ON t.idTopic = c.idTopic
    LEFT JOIN users u ON u.iduser = c.idTeacher
	  WHERE ";
  if($idUser != '') {
    $sql = $sql . "c.idTeacher = ? AND ";
  }
  $sql = $sql . "CONCAT(c.idCourse, ' ', c.nameCourse, ' ', c.price, ' ', t.nameTopic, ' ', u.username) LIKE ?";
  $stmt = mysqli_prepare($conn, $sql);
  if ($stmt) {
    if($idUser != '') {
      mysqli_stmt_bind_param($stmt, "is", $idUser, $search);
    } else {
      mysqli_stmt_bind_param($stmt, "s", $search);
    }
    
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $data = 0;
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $data = $row;
      }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    $totalCourses = $data['total'];
    $totalPages = ceil($totalCourses / 40);
    return $totalPages;
  }
}

function addCourse($nameCourse, $descripCourse, $price, $teacher, $topic) {
  $conn = getDbConnection();

  $sql = "INSERT INTO courses (nameCourse, descrip, price, idTeacher, idTopic) VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssdii", $nameCourse, $descripCourse, $price, $teacher, $topic);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getCourseByPage($total, $start = 0, $valueSearch = '', $idUser = '')
{
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "SELECT c.idCourse, c.nameCourse, c.price, t.nameTopic, t.color, u.idUser, u.username, u.phone, c.createAt
    FROM courses c
    LEFT JOIN topics t ON c.idTopic = t.idTopic
    LEFT JOIN users u ON u.idUser = c.idTeacher ";
	  
    if($idUser != '') {
      $sql = $sql . "WHERE (c.idTeacher = ?) ";
    }
    $sql = $sql . "AND CONCAT(COALESCE(c.nameCourse, '') LIKE ? OR 
    COALESCE(t.nameTopic, '') LIKE ? OR 
    COALESCE(u.idUser, '') LIKE ? OR 
    COALESCE(u.username, '') LIKE ? OR
    CAST(c.idCourse AS CHAR) LIKE ? OR 
    CAST(c.price AS CHAR) LIKE ?)
    ORDER BY c.createAt
    LIMIT ? OFFSET ?;
  ";

  $stmt = mysqli_prepare($conn, $sql);
  if ($stmt) {
    if($idUser != '') {
      mysqli_stmt_bind_param($stmt, "issssssii", $idUser, $search, $search, $search, $search, $search, $search, $total, $start);
    } else {
      mysqli_stmt_bind_param($stmt, "ssssssii", $search, $search, $search, $search, $search, $search, $total, $start);
    }
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $data = [];
    if ($result) {
      while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
      }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $data;
  }

  mysqli_close($conn);
  return NULL;
}

function deleteCourse($id) {
  $conn = getDbConnection();

  $sqlDeleteRatings = "DELETE FROM ratings WHERE idCourse = ?";
  $stmt = mysqli_prepare($conn, $sqlDeleteRatings);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  
  // foreach($ratings as $rating) {
  //   $isDelete = deleteRating($rating['idRating']);
  //   if(!$isDelete) return false;
  // }

  $units = getUnitByIdCourse($id);
  
  foreach($units as $unit) {
    
    $isDelete = deleteUnit($unit['idUnit']);
    if(!$isDelete) return false;
  }

  if(!deleteRegisteredCourseByIdCourse($id)) return false;

  $sql = "DELETE FROM courses WHERE idCourse = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getCourseById($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM courses c
      LEFT JOIN topics t ON c.idTopic = t.idTopic
      LEFT JOIN users u ON c.idTeacher = u.idUser
      WHERE c.idCourse = ? LIMIT 1
    ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $course = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $course;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function getCourseByIdUserAuth($id = '') {
    $conn = getDbConnection();

    $sql = "SELECT *
        FROM courses
      ";

    if($id != '') {
      $sql = "SELECT *
        FROM courses 
        WHERE idTeacher = ?;
      ";
    }

    $stmt = mysqli_prepare($conn, $sql);
    $courses = [];
    if ($stmt) {
      if($id != '') {
        mysqli_stmt_bind_param($stmt, "i", $id);
      }
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      
      if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) { 
          $courses[] = $row;
      }
    }

    mysqli_stmt_close($stmt);
    }

    return $courses;
  }

  function getCourseByIdTopic($id) {
    $conn = getDbConnection();

    $sql = "SELECT *
      FROM courses 
      WHERE idTopic = ?;
    ";
    $stmt = mysqli_prepare($conn, $sql);
    $courses = [];
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "i", $id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      
      if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) { 
          $courses[] = $row;
      }
    }

    mysqli_stmt_close($stmt);
    }

    return $courses;
  }

  function updateCourse($id, $nameCourse, $descrip, $price, $sale, $imgCourse, $topic, $teacher) {
    $conn = getDbConnection();

    $sql = "UPDATE courses SET nameCourse = ?, descrip = ?, price = ?, sale = ?, imgCourse = ?, idTopic = ?, idTeacher = ? WHERE idCourse = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "ssddsiii", $nameCourse, $descrip, $price, $sale, $imgCourse, $topic, $teacher, $id);
      $success = mysqli_stmt_execute($stmt);

      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $success;
    }

    mysqli_close($conn);
    return false;
  }

  function getAllCourses() {
    $conn = getDbConnection();

    $sql = "SELECT * FROM courses
        ORDER BY createAt
      ";

    $result = mysqli_query($conn, $sql);
    $courses = [];
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
      }
    }

    mysqli_close($conn);
    return $courses;
  }

  function getAllCoursesRenderHome() {
    $conn = getDbConnection();

    $sql = "SELECT c.idCourse, c.nameCourse, c.descrip, c.imgCourse, c.price, c.sale, c.createAt, u.username, ro.nameRole, COUNT(r.idCourse) as quantityRated, COALESCE(SUM(r.rated), 5) as totalRated
            FROM courses c
            LEFT JOIN ratings r ON r.idCourse = c.idCourse
            LEFT JOIN users u ON u.idUser = c.idTeacher
            LEFT JOIN roles ro ON ro.idRole = u.idRole AND ro.nameRole = 'Teacher'
            GROUP BY c.idCourse, c.nameCourse, c.descrip, c.imgCourse, c.price, c.sale, c.createAt, u.username, ro.nameRole
            ORDER BY c.createAt";

    $result = mysqli_query($conn, $sql);
    $courses = [];
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
      }
    }

    mysqli_close($conn);
    return $courses;
  }
?>