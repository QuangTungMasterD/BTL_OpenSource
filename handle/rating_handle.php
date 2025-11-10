<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../functions/rating_function.php';

function handleGetRatingByPage($valueSearch = '', $page = 1, $id = '')
{
  return getRatingByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch, $id);
}

function handleAddRating() {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $student = $_POST['student'] ?? '';
  $course = $_POST['course'] ?? '';
  $rated = $_POST['rated'] ?? '';
  $content = $_POST['content'] ?? '';

  $rating = getRatingByIdUserAndCourse($student, $course);

  $_SESSION['old'] = compact('student', 'course', 'rated', 'content');

  if($student == '') $_SESSION['errors']['student'] = 'Vui lòng đăng ký khóa học cho người dùng.';
  if($course == '') $_SESSION['errors']['course'] = 'Vui lòng đăng ký khóa học.';
  if($rated < 0 || $rated > 5) $_SESSION['errors']['rated'] = 'Số sao không phù hợp.';
  if($rated == '') $_SESSION['errors']['rated'] = 'Vui lòng nhập số sao.';
  if($rating != null) $_SESSION['errors']['student'] = 'Người dùng đã có đánh giá cho khóa học này.';
  
  if (empty($_SESSION['errors'])) {
    $isAdd = addRating($course, $student, $rated, $content);
    $page = getTotalPageRating();

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Thêm đánh giá mới thành công." : "Thêm đánh giá mới thất bại.";
    header("Location: ./../views/manager/ratings/index.php?page=" . $page);
    exit;
  }

  header("Location: ./../views/manager/ratings/add.php");
}

function handleAddRatingByUser() {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $student = $_POST['student'] ?? '';
  $course = $_POST['course'] ?? '';
  $rated = $_POST['rated'] ?? '';
  $content = $_POST['content'] ?? '';

  $rating = getRatingByIdUserAndCourse($student, $course);

  $_SESSION['old'] = compact('student', 'course', 'rated', 'content');

  if($student == '') $_SESSION['errors']['student'] = 'Vui lòng đăng ký khóa học cho người dùng.';
  if($course == '') $_SESSION['errors']['course'] = 'Vui lòng đăng ký khóa học.';
  if($rated < 0 || $rated > 5) $_SESSION['errors']['rated'] = 'Số sao không phù hợp.';
  if($rated == '') $_SESSION['errors']['rated'] = 'Vui lòng nhập số sao.';
  if($rating != null) $_SESSION['errors']['student'] = 'Người dùng đã có đánh giá cho khóa học này.';
  
  if (empty($_SESSION['errors'])) {
    $isAdd = addRating($course, $student, $rated, $content);

    $_SESSION['notifi'] = $isAdd ? "Thêm đánh giá thành công." : "Thêm đánh giá thất bại.";
    $_SESSION['isSuccessNotify'] = $isAdd;
  } else {
    $_SESSION['notifi'] = "Thêm đánh giá thất bại.";
    $_SESSION['isSuccessNotify'] = false;
  }

  header("Location: ./../views/course/index.php?id=" . $course);
  exit();
}

function handleDeleteRating($id) {
  $isDeleteRating = deleteRating($id);
  $_SESSION['isSuccessNotify'] = $isDeleteRating;
  $_SESSION['notifi'] = $isDeleteRating ? "Xóa đánh giá thành công" : "Xóa thất bại";
  
  header('Location: ' . ($_SESSION['prev_url']));
}

function handleGetRatingById($id) {
  return getRatingById($id);
}

function handleEditRating($id) {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $student = $_POST['student'] ?? '';
  $course = $_POST['course'] ?? '';
  $rated = $_POST['rated'] ?? '';
  $content = $_POST['content'] ?? '';

  $rating = getRatingByIdUserAndCourse($student, $course);

  $_SESSION['old'] = compact('student', 'course', 'rated', 'content');

  if($student == '') $_SESSION['errors']['student'] = 'Vui lòng tạo người dùng.';
  if($course == '') $_SESSION['errors']['course'] = 'Vui lòng tạo khóa học.';
  if($rated < 0 || $rated > 5) $_SESSION['errors']['rated'] = 'Số sao không phù hợp.';
  if($rated == '') $_SESSION['errors']['rated'] = 'Vui lòng nhập số sao.';
  
  if($rating != null && $rating['idRating'] != $id) $_SESSION['errors']['student'] = 'Người dùng đã có đánh giá cho khóa học này.';
  
  if (empty($_SESSION['errors'])) {
    $isUpdate = updateRating($id, $course, $student, $rated, $content);

    $_SESSION['isSuccessNotify'] = $isUpdate;
    $_SESSION['notifi'] = $isUpdate ? "Sửa đánh giá thành công." : "Sửa đánh giá thất bại.";
    header('Location: ' . $_SESSION['prev_url']);
    exit;
  }

  header('Location: ../views/manager/ratings/edit.php?id=' . $_SESSION['id']);
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'search': {
        if (isset($_GET['s'])) {
          $s = trim($_GET['s']);
          $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
          header('Location: ./../views/manager/ratings/index.php?' . $query . 'page=1');
          exit;
        }
        break;
      }
    case 'delete': {
      if(isset($_GET['id'])) {
        handleDeleteRating($_GET['id']);
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
      if(isset($_POST['by-user'])) {
        handleAddRatingByUser();
      }
      else {
        handleAddRating();
      }
      break;
    }
    case 'edit': {
      if(isset($_POST['id'])) {
        handleEditRating($_POST['id']);
      }
    }
  }
}
?>