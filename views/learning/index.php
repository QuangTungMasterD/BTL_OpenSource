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
if ($lessonLearning == '') {
  $unitsCourse = getUnitByIdCourse($course['idCourse']);
  if(count($unitsCourse) > 0) {
    $lessonsCourses = getLessonByIdUnit($unitsCourse[0]['idUnit']);
    if (count($lessonsCourses) > 0) {
      header('Location: ./index.php?id=' . $idCourse . '&l=' . $lessonsCourses[0]['idLesson']);
    }
  }
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
            <video autoplay class="h-[calc(100vh-var(--height-header))] rounded-md mx-auto" src="./../../<?= $lessonLearning['urlVideo'] ?>" controls></video>
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
                      <? if ($comment['idUser'] == $_SESSION['user_id'] || $_SESSION['role'] == 'Admin') { ?>
                        <button data-modal-target="popup-modal"
                          data-modal-toggle="popup-modal"
                          data-id-comment="<?= htmlspecialchars($comment['idComment']) ?>"
                          class="open-delete-modal text-[12px] underline text-gray-400 hover:text-gray-600" data-id-comment="<?= $comment['idComment']?>">
                          Xóa
                        </button>
                      <? } ?>
                    </div>
                  </div>
                </div>
                <div class="mt-3 text-gray-600">
                  <?= $comment['Content'] ?>
                </div>
              </div>
            <? } ?>

          </div>
          <? if (isset($lessonLearning)) { ?>
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
          <? } ?>
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

  <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
      <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
        <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="p-4 md:p-5 text-center">
          <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <h3 id="content-modal-delete" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400"></h3>
          <button data-modal-hide="popup-modal" type="button" id="confirm-delete" class="delete-comment-btn text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
            Xóa
          </button>
          <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Hủy</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      let openButtons = document.querySelectorAll('.open-delete-modal');
      const confirmDeleteBtn = document.getElementById('confirm-delete');
      const contenModal = document.getElementById('content-modal-delete');

      function handleOpenButtons() {
        openButtons = document.querySelectorAll('.open-delete-modal');
        openButtons.forEach((btn) => {
          btn.onclick = function() {
            const commentId = btn.getAttribute('data-id-comment');
            confirmDeleteBtn.setAttribute('data-id-comment', `${commentId}`);
            contenModal.innerText = `Bạn có chắc chắn muốn xóa bình luận này?`
          }
        })
      }
      handleOpenButtons();

      confirmDeleteBtn.onclick = async () => {
        const currentDeleteId = confirmDeleteBtn.getAttribute('data-id-comment');
        const formData = new FormData();
        formData.append('idComment', currentDeleteId);
        formData.append('action', 'delete');

        try {
          const response = await fetch(`./../../handle/comment_handle.php`, {
            method: 'POST',
            body: formData
          });
          const result = await response.json();

          if (result.success) {
            const btn = document.querySelector(`.open-delete-modal[data-id-comment="${currentDeleteId}"]`);
            if (btn) btn.closest('.border-b').remove();
          } else {
          }
        } catch (err) {
          console.error(err);
        }
      };

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
                <div class="ml-3 text-gray-600 flex">
                  <p>${result.user.username}</p>
                  <div class="ml-4">
                    <button data-modal-target="popup-modal"
                      data-modal-toggle="popup-modal"
                      data-id-comment="${result.comment.id}"
                      class="open-delete-modal text-[12px] underline text-gray-400 hover:text-gray-600" data-id-comment="${result.comment.id}">
                      Xóa
                    </button>
                  </div>
                </div>
              </div>
              <div class="mt-3 text-gray-600">
                ${result.comment.content}
              </div>
            `;
            commentSection.appendChild(newComment);
            if (window.initFlowbite) {
              initFlowbite();
            }
            form.reset();
            handleOpenButtons();
          } else {}
        } catch (error) {
        }
      });
    });
  </script>
</body>

</html>