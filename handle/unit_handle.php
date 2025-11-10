<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../functions/unit_function.php';

function handleGetUnitByPage($valueSearch = '', $page = 1, $idUser)
{
  return getUnitByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch, $idUser);
}

function handleAddUnit() {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $nameUnit = $_POST['nameUnit'] ?? '';
  $course = $_POST['course'] ?? '';
  $order = $_POST['order'] ?? 0;

  $_SESSION['old'] = compact('nameUnit', 'course', 'order');

  if($nameUnit == '') $_SESSION['errors']['nameUnit'] = 'Vui lòng nhập tên chương.';
  if($order == '') $_SESSION['errors']['order'] = 'Vui lòng nhập thứ tự chương.';
  if($order < 0) $_SESSION['errors']['order'] = 'Thứ tự bài không hợp lệ.';
  if($course == '') $_SESSION['errors']['course'] = 'Vui lòng thêm khóa học.';
  
  if(getUnitByIdCourseAndOrder($course, $order) != null) $_SESSION['errors']['order'] = 'Vị trí chương đã tồn tại.';

  if (empty($_SESSION['errors'])) {
    $isAdd = addUnit($nameUnit, $course, $order);
    $page = getTotalPageUnit();

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Thêm chương mới thành công." : "Thêm chương mới thất bại.";
    header("Location: ./../views/manager/units/index.php?page=" . $page);
    exit;
  }

  header("Location: ./../views/manager/units/add.php");
}

function handleGetUnitByIdUser($id = '') {
  return getUnitByIdUser($id);
}

function handleDeleteUnit($id) {
  $isDeleteUnit = deleteUnit($id);
  $_SESSION['isSuccessNotify'] = $isDeleteUnit;
  $_SESSION['notifi'] = $isDeleteUnit ? "Xóa chương thành công" : "Xóa thất bại";

  header('Location: ' . $_SESSION['prev_url']);
}

function handleGetUnitById($id) {
  return getUnitById($id);
}

function handleEditUnit($id) {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $nameUnit = $_POST['nameUnit'] ?? '';
  $course = $_POST['course'] ?? '';
  $order = $_POST['order'] ?? 0;

  $_SESSION['old'] = compact('nameUnit', 'course', 'order');

  if($nameUnit == '') $_SESSION['errors']['nameUnit'] = 'Vui lòng nhập tên chương.';
  if($order == '') $_SESSION['errors']['order'] = 'Vui lòng nhập thứ tự chương.';
  if($order < 0) $_SESSION['errors']['order'] = 'Thứ tự bài không hợp lệ.';
  if($course == '') $_SESSION['errors']['course'] = 'Vui lòng thêm khóa học.';

  $unit = getUnitByIdCourseAndOrder($course, $order);
  if($unit['idUnit'] != $id && $unit != null) $_SESSION['errors']['order'] = 'Vị trí chương đã tồn tại.';

  if (empty($_SESSION['errors'])) {
    $isAdd = updateUnit($id, $nameUnit, $course, $order);

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Sửa chương thành công." : "Sửa chương thất bại.";
    header('Location: ' . $_SESSION['prev_url']);
    exit;
  }

  header('Location: ../views/manager/units/edit.php?id=' . $_SESSION['id']);
}

function handleGetAllUnit() {
  return getAllUnit();
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'search': {
        if (isset($_GET['s'])) {
          $s = trim($_GET['s']);
          $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
          header('Location: ./../views/manager/units/index.php?' . $query . 'page=1');
          exit;
        }
        break;
      }
    case 'delete': {
      if(isset($_GET['id'])) {
        handleDeleteUnit($_GET['id']);
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
      handleAddUnit();
      break;
    }
    case 'edit': {
      if(isset($_POST['id'])) {
        handleEditUnit($_POST['id']);
      }
    }
  }
}

?>