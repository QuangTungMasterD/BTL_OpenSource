<?
require_once __DIR__ . "/db_connection.php";
require_once __DIR__ . "/lesson_function.php";

function getUnitByIdCourse($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM units WHERE idCourse = ? ORDER BY `order`";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $units = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $units[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
  }

  return $units;
}

function deleteUnit($id) {
  $conn = getDbConnection();

  $lessons = getLessonByIdUnit($id);
  
  foreach($lessons as $lesson) {
    $isDelete = deleteLesson($lesson['idLesson']);
    if(!$isDelete) return false;
  }

  $sql = "DELETE FROM units WHERE idUnit = ?";
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

function getUnitByPage($total, $start = 0, $valueSearch = '', $idUser = '') {
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "SELECT u.idUnit, u.nameUnit, u.idCourse, c.nameCourse, u.createAt, us.username FROM units u
    LEFT JOIN courses c ON c.idCourse = u.idCourse
    LEFT JOIN users us ON us.idUser = c.idTeacher WHERE";
    if($idUser != '') {
      $sql = $sql . " c.idTeacher = ? AND";
    }
    $sql = $sql . " CONCAT(u.idUnit, ' ', u.nameUnit, ' ', u.idCourse, ' ', c.nameCourse) LIKE ?
      ORDER BY u.createAt
      LIMIT ? OFFSET ?;";

  $stmt = mysqli_prepare($conn, $sql);
  $data = [];
  if ($stmt) {
    if($idUser != '') {
      mysqli_stmt_bind_param($stmt, "isii", $idUser, $search, $total, $start);
    } else {
      mysqli_stmt_bind_param($stmt, "sii", $search, $total, $start);
    }
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
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
  return $data;
}

function getUnitByIdUser($idUser = '') {
  $conn = getDbConnection();

  $sql = "SELECT u.* FROM units u
    LEFT JOIN courses c ON u.idCourse = c.idCourse";

  if($idUser != '') {
    $sql .= " WHERE c.idTeacher = ?";
  }
  
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    if($idUser != '') {
      mysqli_stmt_bind_param($stmt, "i", $idUser);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $units = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $units[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
  }

  return $units;
}

function getTotalPageUnit($search = '', $idUser = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM units u
	  LEFT JOIN courses c ON c.idCourse = u.idCourse
	  WHERE";
  if($idUser != '') {
    $sql = $sql . " c.idTeacher = ? AND";
  }
  $sql = $sql . " CONCAT(u.idUnit, ' ', u.nameUnit, ' ', u.idCourse, ' ', c.nameCourse) LIKE ?";
  
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
    
    $totalUnits = $data['total'];
    $totalPages = ceil($totalUnits / 40);
    return $totalPages;
  }
}

function getUnitByIdCourseAndOrder($id, $order) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM units WHERE idCourse = ? AND `order` = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $id, $order);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $unit = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $unit;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function addUnit($nameUnit, $idCourse, $order) {
  $conn = getDbConnection();

  $sql = "INSERT INTO units (nameUnit, idCourse, `order`, createAt) VALUES (?, ?, ?, DEFAULT)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sii", $nameUnit, $idCourse, $order);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getUnitById($id) {
  $conn = getDbConnection();

  $sql = "SELECT u.idUnit, u.nameUnit, u.idCourse, c.nameCourse, u.createAt FROM units u
      LEFT JOIN courses c ON c.idCourse = u.idCourse
      WHERE u.idUnit = ? LIMIT 1
    ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $unit = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $unit;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function updateUnit($id, $nameUnit, $course, $order) {
  $conn = getDbConnection();

  $sql = "UPDATE units SET nameUnit = ?, idCourse = ?, `order` = ? WHERE idUnit = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "siii", $nameUnit, $course, $order, $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false; 
}

function getAllUnit() {
  $conn = getDbConnection();

  $sql = "SELECT * FROM units u
      ORDER BY u.createAt
    ";

  $result = mysqli_query($conn, $sql);
  $units = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $units[] = $row;
    }
  }

  mysqli_close($conn);
  return $units;
}

?>