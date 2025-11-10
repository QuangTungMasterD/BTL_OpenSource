<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../functions/user_function.php';
require_once __DIR__ . '/../functions/registered-courses_function.php';

function handleGetUser($valueSearch = '', $page = 1)
{
  return getUserByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch);
}

function handleGetUserByPhone($phone)
{
  return getUserByPhone($phone);
}

function handleGetUserRoleTeacher()
{
  return getUserRoleTeacher();
}

function handleGetUserById($id)
{
  $user = getUserById($id);
  if ($user == null) {
    header('Location: ' . $_SESSION['prev_url']);
  }
  return $user;
}

function handleAddUser()
{
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];
  $username = ($_POST['username'] ?? '');
  $phone = ($_POST['phone'] ?? '');
  $password = ($_POST['password'] ?? '');
  $confirm = ($_POST['confirmpassword'] ?? '');
  $role = $_POST['role'] ?? '';

  $_SESSION['old'] = compact('username', 'phone', 'password', 'confirm', 'role');

  if ($username === '') $_SESSION['errors']['username'] = 'Tên người dùng không được để trống';
  if ($phone === '') $_SESSION['errors']['phone'] = 'Số điện thoại không được để trống';
  if ($password === '') $_SESSION['errors']['password'] = 'Mật khẩu không được để trống';
  if ($password !== $confirm) $_SESSION['errors']['confirm'] = 'Mật khẩu xác nhận không trùng';
  $pattern = '/^0\d{9}$/';
  if (handleGetUserByPhone($phone) != null) $_SESSION['errors']['phone'] = 'Số điện thoại đã tồn tại';
  if (!preg_match($pattern, $phone)) $_SESSION['errors']['phone'] = 'Vui lòng nhập số điện thoại';

  if (empty($_SESSION['errors'])) {
    $isAdd = addUser($username, $password, $phone, $role);
    $page = getTotalPageUser();
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $_SESSION['notifi'] = $isAdd ? "Thêm người dùng mới thành công" : "Thêm người dùng mới thất bại";
    $_SESSION['isSuccessNotify'] = $isAdd;
    header("Location: ./../views/manager/users/index.php?page=" . $page);
    exit;
  }

  header("Location: ./../views/manager/users/add.php");
}

function handleDeleteUser($id)
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $isDeleteUser = deleteUser($id);
  $_SESSION['isSuccessNotify'] = $isDeleteUser;
  $_SESSION['notifi'] = $isDeleteUser ? "Xóa người dùng thành công" : "Xóa thất bại";

  header('Location: ' . $_SESSION['prev_url']);
}

function handleEditUser($id)
{
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $username = $_POST['username'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $avatarFile = $_FILES['avatar'] ?? null;
  $role = $_POST['role'] ?? '';

  $_SESSION['old'] = compact('username', 'phone', 'role');

  if ($username === '') $_SESSION['errors']['username'] = 'Tên người dùng không được để trống';
  if ($phone === '') $_SESSION['errors']['phone'] = 'Số điện thoại không được để trống';

  $pattern = '/^0\d{9}$/';
  $user = handleGetUserByPhone($phone);
  if ($user != null && $user['phone'] == $phone && $user['idUser'] != $id) $_SESSION['errors']['phone'] = 'Số điện thoại đã tồn tại';
  if (!preg_match($pattern, $phone)) $_SESSION['errors']['phone'] = 'Vui lòng nhập số điện thoại';

  if ($avatarFile && $avatarFile['error'] == 0) {
    $targetDir = __DIR__ . '/../uploads/images/users/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $ext = pathinfo($avatarFile['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array(strtolower($ext), $allowed)) {
      $_SESSION['errors']['avatar'] = 'Chỉ được upload ảnh (.jpg, .png, .gif, .webp)';
    } else {
      $fileName = $phone . '.' . $ext;
      $targetFile = $targetDir . $fileName;

      if (file_exists($targetFile)) {
        unlink($targetFile);
      }

      if (move_uploaded_file($avatarFile['tmp_name'], $targetFile)) {
        $avatarPath = 'uploads/images/users/' . $fileName;
      } else {
        $_SESSION['errors']['avatar'] = 'Không thể upload ảnh';
        $avatarPath = 'uploads/images/users/No_Image_Available.jpg';
      }
    }
  } else {
    $currentUser = getUserById($id);
    $avatarPath = $currentUser['avatar'] ?? '/uploads/images/users/No_Image_Available.jpg';
  }

  if (empty($_SESSION['errors'])) {
    updateUser($id, $username, $phone, $avatarPath, $role);

    $_SESSION['notifi'] = "Chỉnh sửa người dùng thành công";
    $_SESSION['isSuccessNotify'] = true;

    header('Location: ' . $_SESSION['prev_url']);
    exit;
  }
  header('Location: ../views/manager/users/edit.php?id=' . $_SESSION['id']);
}

function handleEditUserByUser($id) {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $username = $_POST['username'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $avatarFile = $_FILES['avatar'] ?? null;

  $_SESSION['old'] = compact('username', 'phone');

  if ($username === '') $_SESSION['errors']['username'] = 'Tên người dùng không được để trống';
  if ($phone === '') $_SESSION['errors']['phone'] = 'Số điện thoại không được để trống';

  $pattern = '/^0\d{9}$/';
  $user = handleGetUserByPhone($phone);
  if ($user != null && $user['phone'] == $phone && $user['idUser'] != $id) $_SESSION['errors']['phone'] = 'Số điện thoại đã tồn tại';
  if (!preg_match($pattern, $phone)) $_SESSION['errors']['phone'] = 'Vui lòng nhập số điện thoại';

  $currentUser = getUserById($id);
  if ($avatarFile && $avatarFile['error'] == 0) {
    $targetDir = __DIR__ . '/../uploads/images/users/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $ext = pathinfo($avatarFile['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array(strtolower($ext), $allowed)) {
      $_SESSION['errors']['avatar'] = 'Chỉ được upload ảnh (.jpg, .png, .gif, .webp)';
    } else {
      $fileName = $phone . '.' . $ext;
      $targetFile = $targetDir . $fileName;

      if (file_exists($targetFile)) {
        unlink($targetFile);
      }

      if (move_uploaded_file($avatarFile['tmp_name'], $targetFile)) {
        $avatarPath = 'uploads/images/users/' . $fileName;
      } else {
        $_SESSION['errors']['avatar'] = 'Không thể upload ảnh';
        $avatarPath = 'uploads/images/users/No_Image_Available.jpg';
      }
    }
  } else {
    $avatarPath = $currentUser['avatar'] ?? '/uploads/images/users/No_Image_Available.jpg';
  }

  if (empty($_SESSION['errors'])) {
    updateUser($id, $username, $phone, $avatarPath, $currentUser['idRole']);

    $_SESSION['notifi'] = "Chỉnh sửa thông tin thành công";
    $_SESSION['isSuccessNotify'] = true;
    $_SESSION['imgUser'] = $avatarPath;
    header('Location: ' . $_SESSION['prev_url']);
    exit;
  }
  header('Location: ../views/auth/edit/edit.php');
}

function handleGetAllUser()
{
  return getAllUsers();
}

function handleAddUserByFile()
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $_SESSION['old'] = [];
  $_SESSION['errors'] = [];

  if (!isset($_FILES['file-users']) || $_FILES['file-users']['type'] == '') {
    $_SESSION['errors']['file-users'] = 'Vui lòng chọn file dữ liệu';
    header('Location: ./../views/manager/users/add-file.php');
    exit;
  }

  $fileTmpPath = $_FILES['file-users']['tmp_name'];
  $fileName = $_FILES['file-users']['name'];
  $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

  if ($fileExtension !== 'csv') {
    $_SESSION['errors']['file-users'] = 'Chỉ chấp nhận file csv.';
    // header('Location: ./../views/manager/users/add-file.php');
    exit;
  }



  $file = fopen($fileTmpPath, 'r');
  $rowNumber = 0;
  $inserted = 0;

  while (($data = fgetcsv($file, 1000, ',')) !== false) {
    $rowNumber++;

    if ($rowNumber == 1 && preg_match('/username/i', $data[0])) continue;

    $username = trim($data[0] ?? '');
    $phone = trim($data[1] ?? '');
    $password = trim($data[2] ?? '');
    $role = trim($data[3] ?? '');

    if ($username === '' || $phone === '' || $password === '' || $role === '') {
      continue;
    }

    if (!preg_match('/^0\d{9}$/', $phone)) {
      continue;
    }

    if (handleGetUserByPhone($phone) != null) {
      continue;
    }

    $isAdd = addUser($username, $password, $phone, $role);
    if ($isAdd) $inserted++;
  }

  fclose($file);

  if ($inserted == 0) {
    $_SESSION['isSuccessNotify'] = false;
    $_SESSION['notifi'] = "Thêm người dùng thất bại";
  } else {
    $_SESSION['notifi'] = "Thêm thành công " . $inserted . " người dùng";
    $_SESSION['isSuccessNotify'] = true;
  }

  header('Location: ./../views/manager/users/index.php');
  exit;
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'search': {
        if (isset($_GET['s'])) {
          $s = trim($_GET['s']);
          $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
          header('Location: ./../views/manager/users/index.php?' . $query . 'page=1');
          exit;
        }
        break;
      }
    case 'delete': {
        if (isset($_GET['id'])) {
          handleDeleteUser($_GET['id']);
        }
      }
    default: {
        break;
      }
  }
} elseif (isset($_POST['action']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];
  switch ($action) {
    case 'add': {
        handleAddUser();
        break;
      }
    case 'edit': {
        if (isset($_POST['id']) && !isset($_POST['by-user'])) {
          handleEditUser($_POST['id']);
        }

        if (isset($_POST['by-user'])) {
          if(!isset($_SESSION['user_id'])) {
            $_SESSION['notifi'] = "Vui lòng đăng nhập";
            $_SESSION['isSuccessNotify'] = false;
            header('Location: ./../view/auth/login.php');
          }
          handleEditUserByUser($_POST['id']);
        }
      }
    case 'add-file': {
        $typeFile = $_POST['file'] ?? '';
        if ($typeFile != '') {
          handleAddUserByFile();
        }
      }
  }
}
