<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../functions/comment_function.php';
require_once __DIR__ . '/../functions/user_function.php';

function handleGetCommentByPage($valueSearch = '', $page = 1)
{
  return getCommentByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch);
}

function handleGetCommentByLesson($id) {
  return getCommentByIdLesson($id);
}

function handleAddComment() {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $lesson = $_POST['lesson'] ?? '';
  $user = $_POST['user'] ?? '';
  $content = $_POST['content'] ?? '';
  $parentComment = $_POST['parentComment'] ?? NULL;
  if ($parentComment === '') {
    $parentComment = null;
  }

  $_SESSION['old'] = compact('lesson', 'user', 'content', 'parentComment');

  if($lesson == '') $_SESSION['errors']['lesson'] = 'Vui lòng thêm bài học.';
  if($user == '') $_SESSION['errors']['user'] = 'Vui lòng thêm người dùng.';
  if($content == '') $_SESSION['errors']['content'] = 'Vui lòng nhập nội dung bình luận.';

  if (empty($_SESSION['errors'])) {
    $isAdd = addComment($lesson, $user, $content, $parentComment);
    $page = getTotalPageComment();

    $_SESSION['isSuccessNotify'] = $isAdd;
    $_SESSION['notifi'] = $isAdd ? "Thêm bình luận mới thành công." : "Thêm bình luận mới thất bại.";
    header("Location: ./../views/manager/comments/index.php?page=" . $page);
    exit;
  }

  header("Location: ./../views/manager/comments/add.php");
}

function handleAddCommentByUser() {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $lesson = $_POST['lesson'] ?? '';
  $user = $_SESSION['user_id'];
  $content = $_POST['content'] ?? '';
  $parentComment = $_POST['parentComment'] ?? NULL;
  if ($parentComment === '') {
    $parentComment = null;
  }

  $_SESSION['old'] = compact('lesson', 'user', 'content', 'parentComment');

  if($lesson == '') $_SESSION['errors']['lesson'] = 'Vui lòng thêm bài học.';
  if($user == '') $_SESSION['errors']['user'] = 'Vui lòng thêm người dùng.';
  if($content == '') $_SESSION['errors']['content'] = 'Vui lòng nhập nội dung bình luận.';
  $data = [];
  if (empty($_SESSION['errors'])) {
    $id = addComment($lesson, $user, $content, $parentComment);
    $data['success'] = true;
    $data['user'] = getUserById($user);
    $data['comment']['content'] = $content;
    $data['comment']['id'] = $id;
  } else {
    $data['success'] = false;
  }
  header('Content-Type: application/json');
  echo json_encode($data);
}

function handleDeleteComment($id) {
  $isDeleteComment = deleteComment($id);
  $_SESSION['isSuccessNotify'] = $isDeleteComment;
  $_SESSION['notifi'] = $isDeleteComment ? "Xóa bình luận thành công" : "Xóa thất bại";

  header('Location: ' . $_SESSION['prev_url']);
}

function handleDeleteCommentByUser($id) {
  $isDeleteComment = deleteComment($id);
  $data['success'] = $isDeleteComment;
  header('Content-Type: application/json');
  echo json_encode($data);
}

function handleGetCommentById($id) {
  return getCommentById($id);
}

function handleEditComment($id) {
  $_SESSION['errors'] = [];
  $_SESSION['old'] = [];

  $lesson = $_POST['lesson'] ?? '';
  $user = $_POST['user'] ?? '';
  $content = $_POST['content'] ?? '';
  $parentComment = $_POST['parentComment'] ?? NULL;
  if ($parentComment === '') {
    $parentComment = null;
  }
  $_SESSION['old'] = compact('lesson', 'user', 'content', 'parentComment');

  if($lesson == '') $_SESSION['errors']['lesson'] = 'Vui lòng thêm bài học.';
  if($user == '') $_SESSION['errors']['user'] = 'Vui lòng thêm người dùng.';
  if($content == '') $_SESSION['errors']['content'] = 'Vui lòng nhập nội dung bình luận.';

  if (empty($_SESSION['errors'])) {
    $isUpdate = updateComment($id, $lesson, $user, $content, $parentComment);

    $_SESSION['isSuccessNotify'] = $isUpdate;
    $_SESSION['notifi'] = $isUpdate ? "Sửa bình luận thành công." : "Sửa bình luận thất bại.";
    header('Location: ' . $_SESSION['prev_url']);
    exit;
  }

  header('Location: ../views/manager/comments/edit.php?id=' . $_SESSION['id']);
}

function handleGetAllComment() {
  return getAllComments();
}

$action = '';
if (isset($_GET['action'])) {
  $action = $_GET['action'];

  switch ($action) {
    case 'search': {
        if (isset($_GET['s'])) {
          $s = trim($_GET['s']);
          $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
          header('Location: ./../views/manager/comments/index.php?' . $query . 'page=1');
          exit;
        }
        break;
      }
    case 'delete': {
      if(isset($_GET['id'])) {
        handleDeleteComment($_GET['id']);
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
        handleAddCommentByUser();
      } else {
        handleAddComment();
      }
      break;
    }
    case 'edit': {
      if(isset($_POST['id'])) {
        handleEditComment($_POST['id']);
      }
    }
    case 'delete': {
      if(isset($_POST['idComment'])) {
        handleDeleteCommentByUser($_POST['idComment']);
      }
    }
  }
}
?>