<?
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/rating_function.php';
require_once __DIR__ . '/progress-learns_function.php';
require_once __DIR__ . '/comment_function.php';
require_once __DIR__ . '/registered-courses_function.php';
require_once __DIR__ . '/course_function.php';

function getUserOrderByCreateAt() {
  $conn = getDbConnection();

  $sql = "SELECT u.*, r.*
    FROM users u
    LEFT JOIN roles r ON r.idRole = u.idRole
    WHERE r.nameRole != 'Admin'
    ORDER BY u.createAt
  ";

  $result = mysqli_query($conn, $sql);
  $users = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $users[] = $row;
    }
  }

  mysqli_close($conn);
  return $users;
}

function getCaCulUserByRole()
{
  $conn = getDbConnection();

  $sql = "SELECT u.idRole, r.nameRole, COUNT(*) AS total, COUNT(*) * 100.0 / (SELECT COUNT(*) FROM users) AS percentage
    FROM users u
    LEFT JOIN roles r ON r.idRole = u.idRole
    WHERE r.nameRole != 'Admin'
    GROUP BY u.idRole;
  ";

  $result = mysqli_query($conn, $sql);
  $users = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $users[] = $row;
    }
  }

  mysqli_close($conn);
  return $users;
}

function getTotalUser()
{
  $conn = getDbConnection();
  $sql = "SELECT COUNT(*) AS total FROM users u
    LEFT JOIN roles r ON r.idRole = u.idRole
    WHERE r.nameRole != 'Admin'
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

function getTotalPageUser($search = '')
{
  $conn = getDbConnection();
  $search = "%$search%";
  $sql = "SELECT COUNT(*) AS total FROM users u
    LEFT JOIN roles r ON r.idRole = u.idRole
	  WHERE CONCAT(u.idUser, ' ', u.username, ' ', u.phone, ' ', r.nameRole) LIKE ?
    AND r.nameRole != 'Admin'
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

function getUserByPage($total, $start = 0, $valueSearch = '')
{
  $conn = getDbConnection();

  $search = "%$valueSearch%";
  $sql = "
    SELECT u.idUser, u.username, u.phone, r.nameRole 
    FROM users u
    LEFT JOIN roles r ON r.idRole = u.idRole
	  WHERE CONCAT(u.idUser, ' ', u.username, ' ', u.phone, ' ', r.nameRole) LIKE ?
    AND r.nameRole != 'Admin'
    ORDER BY u.createAt
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

function getUserRoleTeacher()
{
  $conn = getDbConnection();

  $sql = "SELECT u.idUser, u.username, u.phone, r.nameRole FROM users u
      LEFT JOIN roles r ON r.idRole = u.idRole
      WHERE r.nameRole != 'Student'
      ORDER BY u.createAt
    ";

  $result = mysqli_query($conn, $sql);
  $users = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $users[] = $row;
    }
  }

  mysqli_close($conn);
  return $users;
}

function getAllUsers()
{
  $conn = getDbConnection();

  $sql = "SELECT u.idUser, u.username, u.phone, r.nameRole FROM users u
      LEFT JOIN roles r ON r.idRole = u.idRole
      ORDER BY u.createAt
    ";

  $result = mysqli_query($conn, $sql);
  $users = [];
  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $users[] = $row;
    }
  }

  mysqli_close($conn);
  return $users;
}

function addUser($username, $password, $phone, $role = 3)
{
  $conn = getDbConnection();

  $sql = "INSERT INTO users (username, password, phone, idRole, createAt) VALUES (?, ?, ?, ?, DEFAULT)";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $phone, $role);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function getUserById($id)
{
  $conn = getDbConnection();

  $sql = "SELECT u.idUser, u.username, u.phone, u.avatar, r.nameRole, u.idRole FROM users u
      LEFT JOIN roles r ON r.idRole = u.idRole
      WHERE u.idUser = ? LIMIT 1
    ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $user = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $user;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function getUserByPhone($phone)
{
  $conn = getDbConnection();

  $sql = "SELECT u.idUser, u.username, u.phone, r.nameRole FROM users u
      LEFT JOIN roles r ON r.idRole = u.idRole
      WHERE u.phone = ? LIMIT 1
    ";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $user = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);
      mysqli_close($conn);
      return $user;
    }

    mysqli_stmt_close($stmt);
  }

  mysqli_close($conn);
  return null;
}

function updateUser($id, $username, $phone, $avatar, $role = 3)
{
  $conn = getDbConnection();

  $sql = "UPDATE users SET username = ?, phone = ?, avatar = ?, idRole = ? WHERE idUser = ?";
  $stmt = mysqli_prepare($conn, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssii", $username, $phone, $avatar, $role, $id);
    $success = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $success;
  }

  mysqli_close($conn);
  return false;
}

function deleteUser($id)
{
  $conn = getDbConnection();

  $user = getUserById($id);

  if ($user['nameRole'] == 'Teacher') {
    $courses = getCourseByIdUserAuth($id);

    foreach ($courses as $course) {
      $isDelete = deleteCourse($course['idCourse']);
      if (!$isDelete) return false;
    }
  }

  $isDeleteRegis = deleteRegisteredCourseByIdUser($id);
  if (!$isDeleteRegis) return false;

  $comments = getCommentByIdUser($id);
  foreach ($comments as $comment) {
    $isDelete = deleteComment($comment['idComment']);
    if (!$isDelete) return false;
  }

  $ratings = getRatingByIdUser($id);
  foreach ($ratings as $rating) {
    $isDelete = deleteRating($rating['idRating']);
    if (!$isDelete) return false;
  }

  $progressLearns = getProgessByIdStudent($id);

  foreach ($progressLearns as $progressLearn) {
    $isDelete = deleteProgress($progressLearn['idProgress']);
    if (!$isDelete) return false;
  }

  $sql = "DELETE FROM users WHERE idUser = ?";
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
