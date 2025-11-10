<?
require_once 'db_connection.php';
require_once 'rating_function.php';
require_once 'comment_function.php';

function getRegisteresCourseByIdUserRegis($id)
{
  $conn = getDbConnection();

  $sql = "SELECT idRegis, idStudent, idCourse, costed, registerAt 
      FROM registered_courses 
      WHERE idStudent = ?;
    ";
  $stmt = mysqli_prepare($conn, $sql);
  $registerdCourseUser = [];
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $registerdCourseUser[] = $row;
      }
    }

    mysqli_stmt_close($stmt);
  }

  return $registerdCourseUser;
}

function getRegisteresCourseByIdUserAndCourseRegis($student, $course)
{
  $conn = getDbConnection();

  $sql = "SELECT idRegis, idStudent, idCourse, costed, registerAt 
      FROM registered_courses 
      WHERE idStudent = ? AND idCourse = ?;
    ";
  $stmt = mysqli_prepare($conn, $sql);
  
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $student, $course);
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

function deleteRegisteredCourse($id)
{
  $conn = getDbConnection();

  $sql = "DELETE FROM registered_courses WHERE idRegis = ?";

  $registeredCourse = getRegisteredCourseById($id);
  $rating = getRatingByIdUserAndCourse($registeredCourse['idStudent'], $registeredCourse['idCourse']);
  $comments = getCommentByIdUserAndCourse($registeredCourse['idStudent'], $registeredCourse['idCourse']);
  
  foreach($comments as $comment) {
    $isDelete = deleteComment($comment['idComment']);
    if (!$isDelete) return false;
  }

  if($rating != null) {
    $isDelete = deleteRating($rating['idRating']);
    if (!$isDelete) return false;
  }

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

function deleteRegisteredCourseByIdUser($id)
{
  $conn = getDbConnection();

  $sql = "DELETE FROM registered_courses WHERE idStudent = ?";
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

function deleteRegisteredCourseByIdCourse($id)
{
  $conn = getDbConnection();

  $sql = "DELETE FROM registered_courses WHERE idCourse = ?";
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

function getAllRegisteredCourse()
{
  $conn = getDbConnection();

  $sql = "SELECT r.*, u.username, c.nameCourse FROM registered_courses r 
      LEFT JOIN users u ON u.idUser = r.idStudent
      LEFT JOIN courses c ON r.idCourse = c.idCourse";

  $result = mysqli_query($conn, $sql);
  $registeredCourses = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $registeredCourses[] = $row;
    }
  }

  mysqli_close($conn);
  return $registeredCourses;
}

function getUserRegisteredCourseByIdCourse($idCourse = null)
{
  $conn = getDbConnection();

  $sql = "SELECT r.idRegis, r.idCourse, r.costed, u.idUser, u.username 
          FROM registered_courses r 
          LEFT JOIN users u ON u.idUser = r.idStudent";

  if ($idCourse !== null) {
    $sql .= " WHERE r.idCourse = ?";
  }

  $stmt = mysqli_prepare($conn, $sql);

  if ($idCourse !== null) {
    mysqli_stmt_bind_param($stmt, "i", $idCourse);
  }

  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $registeredCourses = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $registeredCourses[] = $row;
    }
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conn);
  return $registeredCourses;
}

function getUserRegisteredCourseByIdLesson($idLesson = null) {
  $conn = getDbConnection();

  $sql = "SELECT r.idRegis, r.idCourse, r.costed, u.idUser, u.username 
          FROM registered_courses r 
          LEFT JOIN units un ON r.idCourse = un.idCourse
          LEFT JOIN lessons l ON l.idUnit = un.idUnit
          LEFT JOIN users u ON u.idUser = r.idStudent";

  if ($idLesson !== null) {
    $sql .= " WHERE l.idLesson = ?";
  }

  $stmt = mysqli_prepare($conn, $sql);

  if ($idLesson !== null) {
    mysqli_stmt_bind_param($stmt, "i", $idLesson);
  }

  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $registeredCourses = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $registeredCourses[] = $row;
    }
  }

  mysqli_stmt_close($stmt);
  mysqli_close($conn);
  return $registeredCourses;
}

function getAllCourseRegisteredCourse()
{
  $conn = getDbConnection();

  $sql = "SELECT DISTINCT c.idCourse, c.nameCourse
    FROM registered_courses r
    LEFT JOIN courses c ON r.idCourse = c.idCourse;";

  $result = mysqli_query($conn, $sql);
  $registeredCourses = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $registeredCourses[] = $row;
    }
  }

  mysqli_close($conn);
  return $registeredCourses;
}

function getRegisteredCourseByPage($total, $start = 0, $valueSearch = '')
{
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "SELECT c.nameCourse, u.username, r.costed, r.idRegis FROM registered_courses r
      LEFT JOIN courses c ON c.idCourse = r.idCourse
      LEFT JOIN users u ON u.idUser = r.idStudent
      WHERE CONCAT(r.idRegis, ' ', u.username, ' ', c.nameCourse, ' ', r.costed) LIKE ?
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

function getTotalPageRegisteredCourse($search = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM registered_courses
      WHERE CONCAT(idRegis, ' ', idStudent, ' ', idCourse, ' ', costed) LIKE ?
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

    $totalTopics = $data['total'];
    $totalPages = ceil($totalTopics / 40);
    return $totalPages;
  }
}

function addRegisteredCourse($idStudent, $idCourse, $costed)
{
  $conn = getDbConnection();

  $sql = "INSERT INTO registered_courses (idStudent, idCourse, costed) VALUES (?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iid", $idStudent, $idCourse, $costed);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getRegisteredCourseById($id)
{
  $conn = getDbConnection();

  $sql = "SELECT * FROM registered_courses
        WHERE idRegis = ? LIMIT 1
      ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
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

function updateRegisteredCourse($id, $idStudent, $idCourse, $costed)
{
  $conn = getDbConnection();

  $sql = "UPDATE registered_courses SET idStudent = ?, idCourse = ?, costed = ? WHERE idRegis = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "iidi", $idStudent, $idCourse, $costed, $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}
