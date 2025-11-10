<?
require_once __DIR__ . '/db_connection.php';

function deleteProgress($id) {
  $conn = getDbConnection();

  $sql = "DELETE FROM progress_learns WHERE idProgress = ?";
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

function getProgessByIdLesson($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM progress_learns WHERE idLesson = ?";
  $stmt = mysqli_prepare($conn, $sql);
  $progress = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $progress[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
  }

  return $progress;
}

function getProgessByIdStudent($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM progress_learns WHERE idStudent = ?";
  $stmt = mysqli_prepare($conn, $sql);
  $progress = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) { 
            $progress[] = $row;
        }
    }

    mysqli_stmt_close($stmt);
  }

  return $progress;
}

function getProgressLearnByIdUserAndLesson($idUser = '', $idLesson = '') {
  $conn = getDbConnection();

  $sql = "SELECT * FROM progress_learns
        WHERE idStudent = ? AND idLesson = ? LIMIT 1
      ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $idUser, $idLesson);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $registeredCourse = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $registeredCourse;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}
?>