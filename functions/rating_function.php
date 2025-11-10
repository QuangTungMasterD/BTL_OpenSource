<?
require_once __DIR__ . "/db_connection.php";

function deleteRating($id) {
  $conn = getDbConnection();

  $sql = "DELETE FROM ratings WHERE idRating = ?";
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

function getRatingByIdCourse($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM ratings WHERE idCourse = ?";
  $stmt = mysqli_prepare($conn, $sql);
  $ratings = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $ratings[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
  }

  return $ratings;
}


function getRatingByIdUser($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM ratings WHERE idStudent = ?";
  $stmt = mysqli_prepare($conn, $sql);
  $ratings = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $ratings[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
  }

  return $ratings;
}

function getRatingByIdUserAndCourse($idStudent, $idCourse) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM ratings WHERE idStudent = ? AND idCourse = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $idStudent, $idCourse);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $rating = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $rating;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function getRatingByPage($total, $start = 0, $valueSearch = '', $idUser = '') {
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "SELECT * FROM ratings r
    LEFT JOIN courses c ON c.idCourse = r.idCourse
    LEFT JOIN users u ON u.idUser = r.idStudent WHERE";
    
  if($idUser != "") {
    $sql .= " c.idTeacher = ? AND";
  }

  $sql .= " CONCAT(idRating, ' ', u.username, ' ', c.nameCourse, ' ', rated) LIKE ?
    LIMIT ? OFFSET ?;
  ";

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

function getTotalPageRating($search = '', $idUser = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM ratings r
    LEFT JOIN courses c ON c.idCourse = r.idCourse WHERE";

  if($idUser != '') {
    $sql .= " c.idTeacher = ? AND";
  }
	$sql .= " CONCAT(r.idRating, ' ', r.idStudent, ' ', r.idCourse, ' ', rated) LIKE ?
  ";
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
    
    $totalTopics = $data['total'];
    $totalPages = ceil($totalTopics / 40);
    return $totalPages;
  }
}

function addRating($idCourse, $idStudent, $rated, $content) {
  $conn = getDbConnection();

  $sql = "INSERT INTO ratings (idCourse, idStudent, rated, content) VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iiis", $idCourse, $idStudent, $rated, $content);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getRatingById($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM ratings
      WHERE idRating = ? LIMIT 1
    ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $rating = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $rating;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function updateRating($id, $idCourse, $idStudent, $rated, $content) {
  $conn = getDbConnection();

  $sql = "UPDATE ratings SET idCourse = ?, idStudent = ?, rated = ?, content = ? WHERE idRating = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iidsi", $idCourse, $idStudent, $rated, $content, $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false; 
}
?>