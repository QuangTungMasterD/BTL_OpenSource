<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../functions/topic_function.php';

function handleGetAllTopics() {
  return getAllTopics();
}

function handleGetTopicByPage($valueSearch = '', $page = 1)
{
  return getTopicByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch);
}

function handleAddTopic() {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $nameTopic = $_POST['nameTopic'] ?? '';
  $color = $_POST['color'] ?? '';

  $_SESSION['old'] = compact('nameTopic', 'color');

  if($nameTopic == '') $_SESSION['errors']['nameTopic'] = 'Vui lòng nhập tên chủ đề.';
  if($color == '') $_SESSION['errors']['color'] = 'Vui lòng nhập màu cho chủ đề.';

  if (empty($_SESSION['errors'])) {
    $isAdd = addTopic($nameTopic, $color);
    $page = getTotalPageTopic();

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Thêm chủ đề mới thành công." : "Thêm chủ đề mới thất bại.";
    header("Location: ./../views/manager/topics/index.php?page=" . $page);
    exit;
  }

  header("Location: ./../views/manager/topics/add.php");
}

function handleDeleteTopic($id) {
  $isDeleteTopic = deleteTopic($id);
  $_SESSION['isSuccessNotify'] = $isDeleteTopic;
  $_SESSION['notifi'] = $isDeleteTopic ? "Xóa chủ đề thành công" : "Xóa thất bại";
  
  header('Location: ' . ($_SESSION['prev_url']));
}

function handleGetTopicById($id) {
  return getTopicById($id);
}

function handleEditTopic($id) {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $nameTopic = $_POST['nameTopic'] ?? '';
  $color = $_POST['color'] ?? '';

  $_SESSION['old'] = compact('nameTopic', 'color');

  if($nameTopic == '') $_SESSION['errors']['nameTopic'] = 'Vui lòng nhập tên chủ đề.';
  if($color == '') $_SESSION['errors']['nameTopic'] = 'Vui lòng màu cho chủ đề.';

  if (empty($_SESSION['errors'])) {
    $isAdd = updateTopic($id, $nameTopic, $color);

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Sửa chủ đề thành công." : "Sửa chủ đề thất bại.";
    header('Location: ' . $_SESSION['prev_url']);
    exit;
  }

  header('Location: ../views/manager/topics/edit.php?id=' . $_SESSION['id']);
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'search': {
        if (isset($_GET['s'])) {
          $s = trim($_GET['s']);
          $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
          header('Location: ./../views/manager/topics/index.php?' . $query . 'page=1');
          exit;
        }
        break;
      }
    case 'delete': {
      if(isset($_GET['id'])) {
        handleDeleteTopic($_GET['id']);
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
      handleAddTopic();
      break;
    }
    case 'edit': {
      if(isset($_POST['id'])) {
        handleEditTopic($_POST['id']);
      }
    }
  }
}
?>