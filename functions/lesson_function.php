<?
require_once __DIR__ . "/db_connection.php";
require_once __DIR__ . "/comment_function.php";
require_once __DIR__ . "/progress-learns_function.php";

function getLessonByIdUnit($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM lessons WHERE idUnit = ? ORDER BY `order`";
  $stmt = mysqli_prepare($conn, $sql);

  $lessons = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $lessons[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
  }

  return $lessons;
}

function deleteLesson($id) {
  $conn = getDbConnection();

  $comments = getCommentByIdLesson($id);
  foreach($comments as $comment) {
    $isDelete = deleteComment($comment['idComment']);
    if(!$isDelete) return false;
  };

  $progressLearns = getProgessByIdLesson($id);
  foreach($progressLearns as $pro) {
    $isSuccess = deleteProgress($pro['idProgress']);
    if(!$isSuccess) return false;
  }

  $sql = "DELETE FROM lessons WHERE idLesson = ?";
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

function getLessonByPage($total, $start = 0, $valueSearch = '', $idUser = '') {
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "SELECT l.idLesson, l.nameLesson, u.nameUnit, l.createAt FROM lessons l
    LEFT JOIN units u ON l.idUnit = u.idUnit
    LEFT JOIN courses c ON c.idCourse = u.idCourse WHERE";
    
  if($idUser != '') {
    $sql .= ' c.idTeacher = ? AND';
  }
  $sql .= " CONCAT(l.idLesson, ' ', l.nameLesson, ' ', u.idUnit, ' ', u.nameUnit, ' ', l.createAt) LIKE ?
    ORDER BY l.createAt
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

function getTotalPageLesson($search = '', $idUser = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM lessons l
	  LEFT JOIN units u ON l.idUnit = u.idUnit
    LEFT JOIN courses c ON c.idCourse = u.idCourse WHERE";
  
  if($idUser != '') {
    $sql .= ' c.idTeacher = ? AND';
  }
  $sql .=" CONCAT(l.idLesson, ' ', l.nameLesson, ' ', u.idUnit, ' ', u.nameUnit) LIKE ?";
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
    
    $totalUsers = $data['total'];
    $totalPages = ceil($totalUsers / 40);
    return $totalPages;
  }
}

function getLessonByIdUnitAndOrder($id, $order) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM lessons WHERE idUnit = ? AND `order` = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $id, $order);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $lesson = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $lesson;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function addLesson($idUnit, $nameLesson, $descrip, $urlVideo, $order) {
  $conn = getDbConnection();

  $sql = "INSERT INTO lessons (idUnit, nameLesson, descrip, urlVideo, `order`, createAt) VALUES (?, ?, ?, ?, ?, DEFAULT)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "isssi", $idUnit, $nameLesson, $descrip, $urlVideo, $order);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getLessonById($id) {
  $conn = getDbConnection();

  $sql = "SELECT l.idLesson, l.nameLesson, l.descrip, l.urlVideo, l.order, u.idUnit, u.nameUnit, u.createAt FROM lessons l
      LEFT JOIN units u ON l.idUnit = u.idUnit
      WHERE l.idLesson = ? LIMIT 1
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

function updateLesson($id, $idUnit, $nameLesson, $descrip, $urlVideo, $order) {
  $conn = getDbConnection();

  $sql = "UPDATE lessons SET idUnit = ?, nameLesson = ?, descrip = ?, urlVideo = ?, `order` = ? WHERE idLesson = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "isssii", $idUnit, $nameLesson, $descrip, $urlVideo, $order, $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false; 
}

function getAllLesson() {
  $conn = getDbConnection();

  $sql = "SELECT * FROM lessons
      ORDER BY createAt
    ";

  $result = mysqli_query($conn, $sql);
  $lessons = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $lessons[] = $row;
    }
  }

  mysqli_close($conn);
  return $lessons;
}
?>