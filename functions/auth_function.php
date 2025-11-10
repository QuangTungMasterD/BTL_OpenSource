<?
  require_once 'db_connection.php';

  function authentication($conn, $phone, $password) {
    $sql = "SELECT u.idUser, u.username, u.phone, u.password, r.nameRole, u.avatar
      FROM users u
      JOIN roles r ON r.idRole = u.idRole
      WHERE u.phone = ? LIMIT 1;";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, "s", $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($password === $user['password']) {
            mysqli_stmt_close($stmt);
            return $user;
        }
    }
    if ($stmt) mysqli_stmt_close($stmt);
    return false;
  }
?>