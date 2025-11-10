<?
require_once __DIR__ . "/db_connection.php";

function getAllCommentByIdParent($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM comments WHERE parentComment = ?";
  $stmt = mysqli_prepare($conn, $sql);

  $comments = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = $row;
      }
    }
    mysqli_stmt_close($stmt);
  }

  return $comments;
}

function getCommentByIdLesson($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM comments WHERE idLesson = ?";
  $stmt = mysqli_prepare($conn, $sql);

  $comments = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $comments[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
  }

  return $comments;
}

function deleteComment($id) {
  $conn = getDbConnection();
  $childentCommnets = getAllCommentByIdParent($id);
  
  foreach($childentCommnets as $childentCommnet) {
    $isDelete = deleteComment($childentCommnet['idComment']);
    if(!$isDelete) return false;
  }
  
  $sql = "DELETE FROM comments WHERE idComment = ?";
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

function getCommentByIdUser($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM comments WHERE idUser = ?";
  $stmt = mysqli_prepare($conn, $sql);

  $comments = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $comments[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
  }

  return $comments;
}

function getCommentByIdUserAndCourse($idStudent, $idCourse) {
  $conn = getDbConnection();

  $sql = "SELECT c.* FROM comments c
    LEFT JOIN lessons l ON c.idLesson = l.idLesson
    LEFT JOIN units u ON l.idUnit = u.idUnit
    LEFT JOIN courses co ON u.idUnit = co.idCourse
    WHERE idUser = ? AND co.idCourse = ?
  ";
  $stmt = mysqli_prepare($conn, $sql);

  $comments = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $idStudent, $idCourse);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $comments[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
  }

  return $comments;
}

function getCommentByPage($total, $start = 0, $valueSearch = '') {
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "SELECT c.idComment, c.Content, l.nameLesson, u.username, c.createAt FROM comments c
    LEFT JOIN lessons l ON c.idLesson = l.idLesson
    LEFT JOIN users u ON u.idUser = c.idUser
	  WHERE CONCAT(c.idComment, ' ', c.Content, ' ', l.idLesson, ' ', u.idUser) LIKE ?
    ORDER BY c.createAt
    LIMIT ? OFFSET ?;
  ";

  $stmt = mysqli_prepare($conn, $sql);
  $data = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sii", $search, $total, $start);
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

function getTotalPageComment($search = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM comments c
	  LEFT JOIN lessons l ON c.idLesson = l.idLesson
    LEFT JOIN users u ON u.idUser = c.idUser
	  WHERE CONCAT(c.idComment, ' ', c.Content, ' ', l.idLesson, ' ', u.idUser) LIKE ?
  ";
  $stmt = mysqli_prepare($conn, $sql);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $search);
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

function addComment($idLesson, $idUser, $Content, $parentComment) {
  $conn = getDbConnection();

  $sql = "INSERT INTO comments (idLesson, idUser, Content, parentComment, updateAt) VALUES (?, ?, ?, ?, DEFAULT)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iisi", $idLesson, $idUser, $Content, $parentComment);
    $success = mysqli_stmt_execute($stmt);
    $insertId = mysqli_insert_id($conn); // Lấy ID vừa insert

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $success ? $insertId : false;
  }

  mysqli_close($conn);
  return false;
}

function getCommentById($id) {
  $conn = getDbConnection();

  $sql = "SELECT c.idComment, c.Content, l.idLesson, c.parentComment, u.idUser, c.createAt FROM comments c
      LEFT JOIN lessons l ON c.idLesson = l.idLesson
      LEFT JOIN users u ON u.idUser = c.idUser
      WHERE c.idComment = ? LIMIT 1
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

function updateComment($id, $idLesson, $idUser, $Content, $parentComment) {
  $conn = getDbConnection();

  $sql = "UPDATE comments SET idLesson = ?, idUser = ?, Content = ?, parentComment = ? WHERE idComment = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iisii", $idLesson, $idUser, $Content, $parentComment, $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false; 
}

function getAllComments() {
  $conn = getDbConnection();

  $sql = "SELECT * FROM comments c
      ORDER BY c.createAt
    ";

  $result = mysqli_query($conn, $sql);
  $comments = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $comments[] = $row;
    }
  }

  mysqli_close($conn);
  return $comments;
}
?>