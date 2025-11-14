<?
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  
  require_once __DIR__ . '/../../handle/auth_handle.php';
  checkLogin();
  require_once __DIR__ . '/../../handle/user_handle.php';
  require_once __DIR__ . '/../../functions/registered-courses_function.php';
  require_once __DIR__ . '/../../functions/course_function.php';
  $_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];
  $idUser = $_GET['id'] ?? '';
  $user = handleGetUserById($idUser);
  if(!isset($user)) {
    $_SESSION['notifi'] = "Bạn cần đăng nhập để truy cập trang này";
    $_SESSION['isSuccessNotify'] = false;
    header('Location: ./login.php');
    exit();
  }
  $registerCourses = getRegisteresCourseByIdUserRegis($idUser);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TDEDU</title>
  <link rel="stylesheet" href="./../../css/global.css">
  <link rel="stylesheet" href="./../../fontawesome-free-7.1.0-web/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <? include "./../components/header.php"; ?>
  <div class="mt-[calc(var(--height-header)+10px)] pb-4 w-[1400px] mx-auto grid grid-cols-5">
    <div class="col-span-1">
      <div class="w-[220px] h-[220px] bg-cover bg-center bg-no-repeat mx-auto rounded-[50%] border border-[#dfdfdf]" style="background-image: url('./../../<?=$user['avatar']?>');"></div>
    </div>
    <div class="col-span-4">
      <div class="mt-6 min-h-[220px]">
        <div class="text-[36px]"><?=$user['username']?></div>
        <div class="py-2 text-gray-600">
          <div class="">Số điện thoại: <?=$user['phone']?></div>
        </div>
        <div class="">
          <a href="./edit.php" class="btn primary inline-block">Sửa thông tin</a>
        </div>
      </div>
      <div class="group-content">
        <div class="text-2xl font-bold ml-3 mb-8 border-b border-[#dfdfdf]">Các khóa đang học</div>
        <div class="">
          <?if(count($registerCourses) == 0) {?>
            <p class="p-4">Chưa đăng ký khóa học nào</p>
          <?}?>
          <?foreach($registerCourses as $re) {
            $course = getCourseById($re['idCourse']);
            $teacher = getUserById($course['idTeacher']);
          ?>
            <div class="grid grid-cols-4 mb-3 gap-6">
              <div class="col-span-1">
                <div class="w-[100%] pt-[100%] bg-cover bg-center bg-no-repeat rounded-md border border-[#dfdfdf]" style="background-image: url('./../../<?=$course['imgCourse']?>');"></div>
              </div>
              <div class="col-span-3 py-4">
                <div class="text-[36px] text-[rgb(var(--primary-color))]"><?=$course['nameCourse']?></div>
                <div class="text-md text-lg text-gray-600"><?=$course['descrip']?></div>
                <div class="text-md text-gray-500 mt-1 mb-3">Giáo viên: <?=$teacher['username']?></div>
                <div class="">
                  <a href="./../learning/index.php?id=<?=$course['idCourse']?>" class="btn primary inline-block">Học</a>
                </div>
              </div>
            </div>
          <?}?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>