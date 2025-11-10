<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../functions/course_function.php';

function handleGetCourse($valueSearch = '', $page = 1, $idUser)
{
  return getCourseByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch, $idUser);
}

function handleGetCourseById($id)
{
  return getCourseById($id);
}

function handleGetCourseByIdUser($id = '')
{
  return getCourseByIdUserAuth($id);
}

function handleDeleteCourse($id)
{
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $isDeleteCourse = deleteCourse($id);

  $_SESSION['isSuccessNotify'] = $isDeleteCourse;
  $_SESSION['notifi'] = $isDeleteCourse ? "Xóa khóa học thành công" : "Xóa kháo học thất bại";

  header('Location: ' . $_SESSION['prev_url']);
}

function handleGetAllCoursesRenderHome()
{
  return getAllCoursesRenderHome();
}

function handleGetAllCourses()
{
  return getAllCourses();
}

function handleAddCourse()
{
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];
  $nameCourse = ($_POST['nameCourse'] ?? '');
  $descripCourse = ($_POST['descripCourse'] ?? '');
  $price = ($_POST['price'] ?? '');
  $teacher = $_POST['teacher'];
  $topic = $_POST['topic'];

  $_SESSION['old'] = compact('nameCourse', 'descripCourse', 'price', 'teacher', 'topic');

  if ($nameCourse === '') $_SESSION['errors']['nameCourse'] = 'Tên khóa học không được để trống';
  if ($descripCourse === '') $_SESSION['errors']['descripCourse'] = 'Chi tiết khóa học không được để trống';
  if ($price < 0) $_SESSION['errors']['price'] = 'Giá không hợp lệ';

  if (empty($_SESSION['errors'])) {
    $isAdd = addCourse($nameCourse, $descripCourse, $price, $teacher, $topic);
    $_SESSION['notifi'] = $isAdd ? "Thêm khóa học thành công." : "Thêm khóa học thất bại.";
    $_SESSION['isSuccessNotify'] = $isAdd;
    $page = getTotalPageCourse();
    header("Location: ./../views/manager/course/index.php?page=" . $page);
    header("Location: ./../views/manager/courses/index.php");
    exit;
  }

  header("Location: ./../views/manager/courses/add.php");
}

function handleEditCourse($id)
{

  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $nameCourse = $_POST['nameCourse'] ?? '';
  $descrip = $_POST['descrip'] ?? '';
  $price = $_POST['price'] ?? 0;
  $sale = $_POST['sale'] ?? 0;
  $imgCourse = $_FILES['imgCourse'] ?? null;
  $topic = $_POST['topic'] ?? '';
  $teacher = $_POST['teacher'] ?? '';

  $_SESSION['old'] = compact('nameCourse', 'descrip', 'price', 'sale', 'imgCourse', 'topic', 'teacher');

  if ($nameCourse === '') $_SESSION['errors']['nameCourse'] = "Vui lòng nhập tên khóa học.";
  if ($descrip === '') $_SESSION['errors']['descrip'] = "Vui lòng nhập nhập chi tiết khóa hoc.";
  if ($topic === '') $_SESSION['errors']['topic'] = "Vui lòng thêm chủ đề mới.";
  if ($teacher === '') $_SESSION['errors']['teacher'] = "Vui lòng thêm giáo viên mới.";
  if ($price < 0) {
    $_SESSION['errors']['price'] = "Giá khóa học không hợp lệ.";
  }
  if ($sale < 0) {
    $_SESSION['errors']['sale'] = "giảm giá khóa học không hợp lệ.";
  }
  if ($imgCourse && $imgCourse['error'] == 0) {
    $targetDir = __DIR__ . '/../uploads/images/courses/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $ext = pathinfo($imgCourse['name'], PATHINFO_EXTENSION);
    $fileName = $id . '.' . $ext;
    $targetFile = $targetDir . $fileName;

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array(strtolower($ext), $allowed)) {
      $_SESSION['errors']['imgCourse'] = "Chỉ được upload ảnh (.jpg, .png, .gif, .webp)";
    } else {
      if (file_exists($targetFile)) {
        unlink($targetFile);
      }

      if (move_uploaded_file($imgCourse['tmp_name'], $targetFile)) {
        $imgPath = 'uploads/images/courses/' . $fileName;
      } else {
        $_SESSION['errors']['imgCourse'] = "Không thể upload ảnh";
      }
    }
  } else {
    $_SESSION['errors']['imgCourse'] = "Vui lòng chọn ảnh đại diện.";
  }


  if (empty($_SESSION['errors'])) {
    updateCourse($id, $nameCourse, $descrip, $price, $sale, $imgPath, $topic, $teacher);
    $_SESSION['notifi'] = "Sửa khóa học thành công";
    $_SESSION['isSuccessNotify'] = true;
    $page = getTotalPageCourse();
    header("Location: ./../views/manager/course/index.php?page=" . $page);
    header("Location: ./../views/manager/courses/index.php");
    exit;
  }

  header("Location: ./../views/manager/courses/edit.php?id=" . $_SESSION['id']);
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'search': {
        if (isset($_GET['s'])) {
          $s = trim($_GET['s']);
          $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
          header('Location: ./../views/manager/courses/index.php?' . $query . 'page=1');
          exit;
        }
        break;
      }
    case 'delete': {
        if (isset($_GET['id'])) {
          handleDeleteCourse($_GET['id']);
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
        handleAddCourse();
        break;
      }
    case 'edit': {
        if (isset($_POST['id'])) {
          handleEditCourse($_POST['id']);
        }
      }
  }
}
