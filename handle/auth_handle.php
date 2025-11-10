<?
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  require_once __DIR__ . '/../functions/db_connection.php';
  require_once __DIR__ . '/../functions/auth_function.php';

  function handleLogOut() {
    if(isLoggedIn()) {
      unset($_SESSION['user_id']);
      unset($_SESSION['phone']);
      unset($_SESSION['role']);
      unset($_SESSION['imgUser']);

      header('Location: ../index.php');
    }
  }

  function checkLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id']) || !isset($_SESSION['phone'])) {
        $_SESSION['notifi'] = 'Bạn cần đăng nhập để truy cập trang này!';
        $_SESSION['isSuccessNotify'] = false;
        header('Location: /BTL-N2/views/auth/login.php');
        exit();
    }
  }

  function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $isLogin = isset($_SESSION['user_id']) && isset($_SESSION['phone']);
    return $isLogin;
  }

  function isTeacherLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['user_id']) && isset($_SESSION['phone']) && ($_SESSION['role'] == 'Teacher' || $_SESSION['role'] == 'Admin')) {
      $_SESSION['notifi'] = 'Bạn Không có quyền truy cập trang!';
      $_SESSION['isSuccessNotify'] = false;
      header('Location: /BTL-N2/index.php');
      exit();
    }
  }

  function isAdminLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if(!(isset($_SESSION['user_id']) && isset($_SESSION['phone']) && $_SESSION['role'] == 'Admin')) {
      $_SESSION['notifi'] = 'Bạn Không có quyền truy cập trang!';
      $_SESSION['isSuccessNotify'] = false;
      header('Location: /BTL-N2/index.php');
      exit();
    }
  }

  function handleLogin() {
    $conn = getDbConnection();
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($phone) || empty($password)) {
        $_SESSION['error'] = 'Vui lòng nhập đầy đủ phone và password!';
        header('Location: ../views/auth/login.php');
        exit();
    }

    $user = authentication($conn, $phone, $password);
    if ($user != null) {
        $_SESSION['user_id'] = $user['idUser'];
        $_SESSION['phone'] = $user['phone'];
        $_SESSION['role'] = $user['nameRole'];
        $_SESSION['imgUser'] = $user['avatar'];
        mysqli_close($conn);
        header('Location: ' . ($_SESSION['prev_url'] ?? '../index.php'));
        exit();
    }

    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
    mysqli_close($conn);
    header('Location: ../views/auth/login.php');
    exit();
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    handleLogin();
  }
  else if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
    handleLogOut();
  }
?>