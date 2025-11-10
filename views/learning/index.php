<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];

require_once __DIR__ . '/../../handle/auth_handle.php';
checkLogin();
require_once __DIR__ . '/../../handle/course_handle.php';
require_once __DIR__ . '/../../handle/user_handle.php';
require_once __DIR__ . '/../../handle/registered-course_handle.php';
require_once __DIR__ . '/../../handle/unit_handle.php';
require_once __DIR__ . '/../../handle/lesson_handle.php';
require_once __DIR__ . '/../../functions/progress-learns_function.php';
require_once __DIR__ . '/../../handle/comment_handle.php';
$idCourse = $_GET['id'] ?? '';
$idUser = $_SESSION['user_id'];
$course = handleGetCourseById($idCourse);
$lessonLearning = getLessonById($_GET['l'] ?? '');
$comments = handleGetCommentByLesson($lessonLearning['idLesson'] ?? '');
if (!isset($course)) {
  $_SESSION['notifi'] = "Khóa học không tồn tại";
  $_SESSION['isSuccessNotify'] = false;
  header('Location: ./../../index.php');
  exit();
}
$currentUser = handleGetUserById($idUser);
$registerCourse = getRegisteresCourseByIdUserAndCourseRegis($idUser, $idCourse);
if (!isset($registerCourse)) {
  $_SESSION['notifi'] = "Bạn chưa đăng ký khóa học này";
  $_SESSION['isSuccessNotify'] = false;
  header('Location: ./../../index.php');
  exit();
}

$units = getUnitByIdCourse($course['idCourse']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TDEDU</title>
  <link rel="stylesheet" href="./../../css/global.css">
  <link rel="stylesheet" href="./../../css/learning/index.css">
  <link rel="stylesheet" href="./../../fontawesome-free-7.1.0-web/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <? include "./../components/header.php"; ?>
  <div class="mt-[calc(var(--height-header)+10px)] pb-4">
    <div class="content flex flex-row-reverse w-[1600px] mx-auto grid grid-cols-4 gap-2">
      <div class="grid col-span-3">
        <div class="col-span-3 bg-black h-[calc(100vh-var(--height-header))] w-[100%] rounded-md relative">
          <? if (isset($lessonLearning['urlVideo']) && $lessonLearning['urlVideo'] != '') { ?>
            <video class="h-[calc(100vh-var(--height-header))] rounded-md mx-auto" src="./../../<?= $lessonLearning['urlVideo'] ?>" controls></video>
          <? } else { ?>
            <div class="h-[calc(100vh-var(--height-header))] rounded-md text-3xl text-white left-[50%] select-lesson-notify">Hãy chọn bài học</div>
          <? } ?>
        </div>
        <? if (isset($lessonLearning)) { ?>
          <div class="col-span-3 text-2xl mt-2"><?= $lessonLearning['nameLesson'] ?></div>
          <div class="col-span-3 text-md mt-1 text-gray-600"><?= $lessonLearning['descrip'] ?></div>
        <? } ?>
        <div class="content-comment mt-12 col-span-3">
          <div class="p-4 border border-[#ccc] w-[100%] bg-[#fcfcfc] rounded-lg comment-content">
            <div class="text-xl">BÌNH LUẬN <? if (count($comments) <= 0) { ?><p class="text-sm text-gray-600">(Không có bình luận nào)</p><? } ?></div>
            <? foreach ($comments as $comment) {
              $userComment = handleGetUserById($comment['idUser']);
            ?>
              <div class="border-b border-[#dfdfdf] mt-8">
                <div class="flex items-center">
                  <div class="bg-center bg-no-repeat bg-cover w-[44px] h-[44px] rounded-[50%] border border-[#dfdfdf]" style="background-image: url('./../../<?= $userComment['avatar'] ?>');"></div>
                  <div class="ml-3 text-gray-600 flex">
                    <p><?= $userComment['username'] ?></p>
                    <div class="ml-4">
                      <?if($comment['idUser'] == $_SESSION['user_id'] || $_SESSION['role'] == 'Admin') {?>
                        <a href="./../../handle/comment_handle.php?id=<?=$comment['idComment']?>&action=delete" class="text-[12px] underline text-gray-400 hover:text-gray-600">Xóa</a>  
                      <?}?>
                    </div>
                  </div>
                </div>
                <div class="mt-3 text-gray-600">
                  <?= $comment['Content'] ?>
                </div>
              </div>
            <? } ?>
            
          </div>
          <?if(isset($lessonLearning)) {?>
            <form class="block mt-6" id="add-comment">
              <div class="flex items-center">
                <div class="bg-center bg-no-repeat bg-cover w-[44px] h-[44px] rounded-[50%] border border-[#dfdfdf]" style="background-image: url('./../../<?= $currentUser['avatar'] ?>');"></div>
                <div class="ml-3 text-gray-600"><?= $currentUser['username'] ?></div>
              </div>
              <div class="mt-3 text-gray-600">
                <input name="content" type="text" class="w-full px-4 py-2 border-b border-[#555] outline-none">
                <input name="lesson" type="hidden" value="<?= $_GET['l'] ?>">
                <input name="action" type="hidden" value="add">
                <input name="by-user" type="hidden" value="">
                <button type="submit" class="btn primary inline-block mt-2">Gửi</button>
              </div>
            </form>
          <?}?>
        </div>
      </div>
      <div class="col-span-1">
        <div class="">
          <div class="text-3xl mb-2 header-sidebar text-[rgb(var(--primary-color))]"><?= $course['nameCourse'] ?></div>
          <? foreach ($units as $unit) {
            $lessons = getLessonByIdUnit($unit['idUnit']);
          ?>
            <div x-data="{ open: false }" class="w-100% mb-1">
              <button @click="open = !open" class="w-[100%] flex items-center justify-start px-6 py-4 border border-md border-[#d0d0d0] text-gray-600 hover:text-black rounded-md hover:bg-gray-100">
                <p class="truncate"><? if (count($lessons) > 0) { ?><i class="fa-solid fa-plus mr-2"></i><? } ?> <?= $unit['nameUnit'] ?></p>
              </button>
              <? foreach ($lessons as $lesson) {
              ?>
                <a href="./index.php?id=<?= $idCourse ?>&l=<?= $lesson['idLesson'] ?>" x-show="open" x-transition class="flex items-center mt-1 pl-10 pr-6 py-3 bg-[#fbfbfb] hover:bg-[#efefef] border border-[#f2f2f2] rounded">
                  <p class="truncate"><i class="fa-solid fa-minus mr-2"></i> <?= $lesson['nameLesson'] ?></p>
                </a>
              <? } ?>
            </div>

          <? } ?>
        </div>
      </div>
    </div>
  </div>

  <? include "./../components/footer.php"; ?>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const form = document.getElementById('add-comment');

      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        try {
          const response = await fetch('./../../handle/comment_handle.php', {
            method: 'POST',
            body: formData
          });

          const result = await response.json();
          
          if (result.success) {
            const commentSection = document.querySelector('.comment-content');
            const newComment = document.createElement('div');
            newComment.className = 'border-b border-[#dfdfdf] mt-8';
            newComment.innerHTML = `
              <div class="flex items-center">
                <div class="bg-center bg-no-repeat bg-cover w-[44px] h-[44px] rounded-[50%] border border-[#dfdfdf]" style="background-image: url('./../../${result.user.avatar}');"></div>
                <div class="ml-3 text-gray-600">${result.user.username}</div>
              </div>
              <div class="mt-3 text-gray-600">
                ${result.comment.content}
              </div>
            `;
            commentSection.appendChild(newComment);

            form.reset();
          } else {
          }
        } catch (error) {
          alert('Có lỗi xảy ra, vui lòng thử lại!');
        }
      });
    });
  </script>
</body>

</html>