<?
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  require_once __DIR__ . '/../functions/registered-courses_function.php';

  function handleDeleteRegisteredCourseByIdUser($id) {
    return deleteRegisteredCourseByIdUser($id);
  }

  function handleGetAllRegisteredCourse() {
    return getAllRegisteredCourse();
  }

  function handleGetUserRegisteredCourseByIdCourse($id) {
    return getUserRegisteredCourseByIdCourse($id);
  }

  function handleGetAllCourseRegisteredCourse() {
    return getAllCourseRegisteredCourse();
  }

  function handleGetRegisteredCourseByPage($valueSearch = '', $page = 1)
  {
    return getRegisteredCourseByPage($total = 40, $start = (40 * ($page - 1)), $valueSearch);
  }

  function handleAddRegisteredCourse() {
    $_SESSION['errors'] = [];
    $_SESSION['old'] = [];

    $student = $_POST['student'] ?? '';
    $course = $_POST['course'] ?? '';
    $costed = $_POST['costed'] ?? 0;

    $_SESSION['old'] = compact('student', 'course', 'costed');

    if($student == '') $_SESSION['errors']['student'] = 'Vui lòng đăng ký khóa học cho người dùng.';
    if($course == '') $_SESSION['errors']['course'] = 'Vui lòng đăng ký khóa học.';
    if($costed < 0) $_SESSION['errors']['costed'] = 'Giá đăng ký không hợp lý.';
    $registered = getRegisteresCourseByIdUserAndCourseRegis($student, $course);
    if($registered != null && $registered['idStudent'] == $student) {
      $_SESSION['errors']['student'] = 'Người dùng đã đăng ký khóa học.';
    }
    
    if (empty($_SESSION['errors'])) {
      $isAdd = addRegisteredCourse($student, $course, $costed);
      $page = getTotalPageRegisteredCourse();

      $_SESSION['isSuccessNotify'] = $isAdd;
      $_SESSION['notifi'] = $isAdd ? "Đăng ký khóa học mới thành công." : "Đăng ký khóa học mới thất bại.";
      if(isset($_POST['register-by'])) {
        header("Location: ./../views/learning/index.php?id=" . $course);
        exit;
      }
      header("Location: ./../views/manager/registered-courses/index.php?page=" . $page);
      exit;
    }

    if(isset($_POST['register-by'])) {
      $_SESSION['isSuccessNotify'] = false;
      $_SESSION['notifi'] = "Bạn đã đăng ký khóa học này";
      header("Location: " . $_SESSION['prev_url']);
      exit;
    }
    header("Location: ./../views/manager/registered-courses/add.php");
  }

  function handleDeleteRegisteredCourse($id) {
    $isDeleteRegisteredCourse = deleteRegisteredCourse($id);
    $_SESSION['isSuccessNotify'] = $isDeleteRegisteredCourse;
    $_SESSION['notifi'] = $isDeleteRegisteredCourse ? "Xóa đăng ký khóa học thành công" : "Xóa thất bại";
    
    header('Location: ' . ($_SESSION['prev_url']));
  }

  function handleGetRegisteredCourseById($id) {
    return getRegisteredCourseById($id);
  }

  function handleEditRegisteredCourse($id) {
    $_SESSION['errors'] = [];
    $_SESSION['old'] = [];

    $student = $_POST['student'] ?? '';
    $course = $_POST['course'] ?? '';
    $costed = $_POST['costed'] ?? '';

    $_SESSION['old'] = compact('student', 'course', 'costed');

    if($student == '') $_SESSION['errors']['student'] = 'Vui lòng đăng ký khóa học cho người dùng.';
    if($course == '') $_SESSION['errors']['course'] = 'Vui lòng đăng ký khóa học.';
    if($costed < 0) $_SESSION['errors']['costed'] = 'Giá đăng ký không hợp lý.';
    $registeredUpdate = getRegisteresCourseByIdUserAndCourseRegis($student, $course);
    if($registeredUpdate != null && $registeredUpdate['idRegis'] != $id) {
      $_SESSION['errors']['student'] = 'Người dùng đã đăng ký khóa học.';
    }
    
    if (empty($_SESSION['errors'])) {
      $registered = getRegisteredCourseById($id);
      if($registeredUpdate == null && ($registered['idStudent'] != $student || $registered['idCourse'] != $course)) {
        $rating = getRatingByIdUserAndCourse($registered['idStudent'], $registered['idCourse']);
        if($rating != null) {
          deleteRating($rating['idRating']);
        }
      }
      $isUpdate = updateRegisteredCourse($id, $student, $course, $costed);

      $_SESSION['isSuccessNotify'] = $isUpdate;
      $_SESSION['notifi'] = $isUpdate ? "Sửa đăng ký khóa học thành công." : "Sửa đăng ký khóa học thất bại.";
      header('Location: ' . $_SESSION['prev_url']);
      exit;
    }

    header('Location: ../views/manager/registered-courses/edit.php?id=' . $_SESSION['id']);
  }

  function handleGetUserRegisteredCourseByIdLesson($id) {
    return getUserRegisteredCourseByIdLesson($id);
  }

  $action = '';
  if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
      case 'search': {
          if (isset($_GET['s'])) {
            $s = trim($_GET['s']);
            $query = $s === '' ? '' : 's=' . urlencode($s) . '&';
            header('Location: ./../views/manager/registered-courses/index.php?' . $query . 'page=1');
            exit;
          }
          break;
        }
      case 'delete': {
        if(isset($_GET['id'])) {
          handleDeleteRegisteredCourse($_GET['id']);
        }
      }
      case 'get-users-registered': {
        if(isset($_GET['idCourse'])) {
          $users = handleGetUserRegisteredCourseByIdCourse($_GET['idCourse']);
          header('Content-Type: application/json');
          echo json_encode($users);
          exit;
        } else if(isset($_GET['idLesson'])) {
          $users = handleGetUserRegisteredCourseByIdLesson($_GET['idLesson']);
          header('Content-Type: application/json');
          echo json_encode($users);
          exit;
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
        handleAddRegisteredCourse();
        break;
      }
      case 'edit': {
        if(isset($_POST['id'])) {
          handleEditRegisteredCourse($_POST['id']);
        }
      }
    }
  }
?>