<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../functions/lesson_function.php';

function handleGetLessonByPage($valueSearch = '', $page = 1, $idUser = '')
{
  return getLessonByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch, $idUser);
}

function handleAddLesson()
{
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $nameLesson = $_POST['nameLesson'] ?? '';
  $unit = $_POST['unit'] ?? '';
  $order = $_POST['order'] ?? 0;
  $descrip = $_POST['descrip'] ?? '';

  $_SESSION['old'] = compact('unit', 'nameLesson', 'descrip', 'urlVideo', 'order');

  if ($nameLesson == '') $_SESSION['errors']['nameLesson'] = 'Vui lòng nhập tên bài học.';
  if ($order == '') $_SESSION['errors']['order'] = 'Vui lòng nhập thứ tự bài học.';
  if ($unit == '') $_SESSION['errors']['unit'] = 'Vui lòng thêm chương mới.';

  if ($order < 0) $_SESSION['errors']['order'] = 'Thứ tự bài không hợp lệ.';

  if (getLessonByIdUnitAndOrder($unit, $order) != null) $_SESSION['errors']['order'] = 'Vị trí bài học đã tồn tại.';
  $videoFile = $_FILES['urlVideo'] ?? null;
  $videoPath = '';
  if ($videoFile && $videoFile['error'] == 0) {
    $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
    if (!in_array($videoFile['type'], $allowedTypes)) {
      $_SESSION['errors']['urlVideo'] = 'Chỉ chấp nhận định dạng video mp4, webm, ogg.';
    } else {
      $uploadDir = __DIR__ . '/../uploads/videos/lessons/';
      if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

      $ext = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
      $fileName = uniqid('video_') . '.' . $ext;
      $targetFile = $uploadDir . $fileName;

      if (move_uploaded_file($videoFile['tmp_name'], $targetFile)) {
        $videoPath = 'uploads/videos/lessons/' . $fileName;
      } else {
        $_SESSION['errors']['urlVideo'] = 'Upload video thất bại.';
      }
    }
  } else {
    $_SESSION['errors']['urlVideo'] = 'Vui lòng chọn video bài học.';
  }
  if (empty($_SESSION['errors'])) {
    $isAdd = addLesson($unit, $nameLesson, $descrip, $videoPath, $order);
    $page = getTotalPageLesson();

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Thêm bài học mới thành công." : "Thêm bài học mới thất bại.";
    header("Location: ./../views/manager/lessons/index.php?page=" . $page);
    exit;
  }

  header("Location: ./../views/manager/lessons/add.php");
}

function handleDeleteLesson($id)
{
  $isDeleteLesson = deleteLesson($id);
  $_SESSION['isSuccessNotify'] = $isDeleteLesson;
  $_SESSION['notifi'] = $isDeleteLesson ? "Xóa bài học thành công" : "Xóa thất bại";

  header('Location: ' . $_SESSION['prev_url']);
}

function handleGetLessonById($id)
{
  return getLessonById($id);
}

function handleEditLesson($id)
{
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $nameLesson = $_POST['nameLesson'] ?? '';
  $unit = $_POST['unit'] ?? '';
  $order = $_POST['order'] ?? 0;
  $descrip = $_POST['descrip'] ?? '';
  $order = $_POST['order'] ?? '';

  $_SESSION['old'] = compact('unit', 'nameLesson', 'descrip', 'urlVideo', 'order');

  if ($nameLesson == '') $_SESSION['errors']['nameLesson'] = 'Vui lòng nhập tên bài học.';
  if ($order == '') $_SESSION['errors']['order'] = 'Vui lòng nhập thứ tự bài học.';
  if ($order < 0) $_SESSION['errors']['order'] = 'Thứ tự bài không hợp lệ.';
  if ($unit == '') $_SESSION['errors']['unit'] = 'Vui lòng thêm chương mới.';

  $videoFile = $_FILES['urlVideo'] ?? null;
  $videoPath = '';
  if ($videoFile && $videoFile['error'] == 0) {
    $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
    if (!in_array($videoFile['type'], $allowedTypes)) {
      $_SESSION['errors']['urlVideo'] = 'Chỉ chấp nhận định dạng video mp4, webm, ogg.';
    } else {
      $uploadDir = __DIR__ . '/../uploads/videos/lessons/';
      if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

      $ext = pathinfo($videoFile['name'], PATHINFO_EXTENSION);
      $fileName = uniqid('video_') . '.' . $ext;
      $targetFile = $uploadDir . $fileName;

      if (move_uploaded_file($videoFile['tmp_name'], $targetFile)) {
        $videoPath = 'uploads/videos/lessons/' . $fileName;
        $pathPart = getLessonById($id)['urlVideo'];
      } else {
        $_SESSION['errors']['urlVideo'] = 'Upload video thất bại.';
      }
    }
  }

  $lesson = getLessonByIdUnitAndOrder($unit, $order);
  if ($lesson['idLesson'] != $id && $lesson != null) $_SESSION['errors']['order'] = 'Vị trí bài học đã tồn tại.';

  if (empty($_SESSION['errors'])) {
    if(isset($videoPath) && $videoPath != '' && $videoPath != null) {
      $isAdd = updateLesson($id, $unit, $nameLesson, $descrip, $videoPath, $order);
    } else {
      $isAdd = updateLesson($id, $unit, $nameLesson, $descrip, $lesson['urlVideo'], $order);
    }

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Sửa chương thành công." : "Sửa chương thất bại.";
    if ($pathPart && file_exists(__DIR__ . '/..' . $pathPart)) {
      unlink(__DIR__ . '/..' . $pathPart);
    }
    header('Location: ' . $_SESSION['prev_url']);
    exit;
  }

  header('Location: ../views/manager/lessons/edit.php?id=' . $_SESSION['id']);
}

function handleGetAllLesson()
{
  return getAllLesson();
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'search': {
        if (isset($_GET['s'])) {
          $s = trim($_GET['s']);
          $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
          header('Location: ./../views/manager/lessons/index.php?' . $query . 'page=1');
          exit;
        }
        break;
      }
    case 'delete': {
        if (isset($_GET['id'])) {
          handleDeleteLesson($_GET['id']);
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
        handleAddLesson();
        break;
      }
    case 'edit': {
        if (isset($_POST['id'])) {
          handleEditLesson($_POST['id']);
        }
      }
  }
}
