<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'db_connection.php';
require_once 'course_function.php';
function getAllTopics() {
  $conn = getDbConnection();

  $sql = "SELECT idTopic, nameTopic, color FROM topics";

  $result = mysqli_query($conn, $sql);
  $topics = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $topics[] = $row;
    }
  }

  mysqli_close($conn);
  return $topics;
}

function getTotalTopic() {
  $conn = getDbConnection();

  $sql = "SELECT count(*) as total FROM topics";

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

function deleteTopic($id) {
  $conn = getDbConnection();

  $courses = getCourseByIdTopic($id);
  
  foreach($courses as $course) {
    $isUpdate = updateCourse($course['idCourse'], $course['nameCourse'], $course['descrip'], $course['price'] , $course['sale'] , $course['imgCourse'] , null , $course['idTeacher']);
    if(!$isUpdate) return false;
  }

  $sql = "DELETE FROM topics WHERE idtopic = ?";
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

function getTopicByPage($total, $start = 0, $valueSearch = '') {
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "SELECT * FROM topics
	  WHERE CONCAT(idTopic, ' ', nameTopic, ' ', color) LIKE ?
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

function getTotalPageTopic($search = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM topics
	  WHERE CONCAT(idTopic, ' ', nameTopic) LIKE ?
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

function addTopic($nameTopic, $color) {
  $conn = getDbConnection();

  $sql = "INSERT INTO topics (nameTopic, color) VALUES (?, ?)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $nameTopic, $color);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getTopicById($id) {
  $conn = getDbConnection();

  $sql = "SELECT * FROM topics
      WHERE idTopic = ? LIMIT 1
    ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $topic = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $topic;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function updateTopic($id, $nameTopic, $color) {
  $conn = getDbConnection();

  $sql = "UPDATE topics SET nameTopic = ?, color = ? WHERE idTopic = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssi", $nameTopic, $color, $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false; 
}
?>