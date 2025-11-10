<?
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../../handle/auth_handle.php';
require_once __DIR__ . '/../../handle/course_handle.php';
require_once __DIR__ . '/../../handle/rating_handle.php';
require_once __DIR__ . '/../../handle/user_handle.php';
require_once __DIR__ . '/../../handle/unit_handle.php';
require_once __DIR__ . '/../../handle/lesson_handle.php';
require_once __DIR__ . '/../../handle/registered-course_handle.php';
checkLogin();

$idCourse = $_GET['id'] ?? '';
$course = handleGetCourseById($idCourse);
if (!isset($course)) {
  header('Location: ./../../index.php');
}
$registeredCourse = getRegisteresCourseByIdUserAndCourseRegis($_SESSION['user_id'], $course['idCourse']);
$ratings = getRatingByIdCourse($course['idCourse']);
$userAuth = getUserById($course['idTeacher']);
$currentUser = handleGetUserById($_SESSION['user_id']);
$ratingUserCourse = getRatingByIdUserAndCourse($_SESSION['user_id'], $idCourse);
$units = getUnitByIdCourse($course['idCourse']);
$rated = count($ratings) == 0 ? 5 : 0;
foreach ($ratings as $rating) { {
    $rated += $rating['rated'];
  }
}
$_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];

$rated /= count($ratings) > 0 ? count($ratings) : 1;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $course['nameCourse'] ?></title>
  <link rel="stylesheet" href="./../../css/global.css">
  <link rel="stylesheet" href="./../../fontawesome-free-7.1.0-web/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <style>
    #stars i:hover,
    #stars i:hover~i {
      color: #fbbf24;
    }
  </style>
</head>

<body>
  <? include "./../components/header.php"; ?>
  <div class="mt-[calc(var(--height-header)+10px)] pb-4 w-[1400px] mx-auto">
    <div class="content flex bg-white p-4 rounded-md">
      <div class="img mr-4 sticky top-[calc(var(--height-header)+6px)] self-start">
        <div class="name h-[200px] w-[200px] bg-center bg-no-repeat bg-cover rounded-[8px] border border-[1px] border-[#dfdfdf]" style="background-image: url('./../../<?= $course['imgCourse'] ?>');"></div>
      </div>
      <div class="info flex-1">
        <div class="min-h-[200px]">
          <div class="name text-[34px]"><?= $course['nameCourse'] ?></div>
          <div class="desc text-[16px] mt-1 mb-2"><?= $course['descrip'] ?></div>
          <div class="costed mt-4">
            <? if ($course['sale'] > 0 && $course['price'] > 0) { ?>
              <div class="card-price mr-2 line-through text-gray-500">
                <?= htmlspecialchars((($course['price'] == 0) ? 'Miễn phí' : $course['price'] . 'đ')) ?>
              </div>
              <div class="card-costed text-[rgb(var(--primary-color))] font-bold text-3xl">
                <?= htmlspecialchars((($course['price'] == 0) ? 'Miễn phí' : ($course['price'] * (100 - $course['sale']) / 100) . 'đ')) ?>
              </div>
            <? } else if ($course['sale'] == 0) { ?>
              <div class="card-costed ml-0 text-[rgb(var(--primary-color))] font-bold text-3xl">
                <?= htmlspecialchars((($course['price'] == 0) ? 'Miễn phí' : ($course['price']) . 'đ')) ?>
              </div>
            <? } ?>
          </div>
          <div class="auth text-gray-600 mt-auto text-sm">Giáo viên: <?= $userAuth['idUser'] ?></div>
          <div class="auth text-gray-600 mt-auto">
            <? if (isset($registeredCourse)) { ?>
              <a href="./../learning/index.php?id=<?= $course['idCourse'] ?>" class="btn primary mt-2 mb-8 inline-block">Học</a>
            <? } else { ?>
              <form action="./../../handle/registered-course_handle.php" method="POST">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="register-by" value="student">
                <input type="hidden" name="course" value="<?= $course['idCourse'] ?>">
                <input type="hidden" name="student" value="<?= $_SESSION['user_id'] ?>">
                <input type="hidden" name="costed" value="<?= $course['price'] * ($course['sale'] ?? 100) / 100 ?>">
                <button class="btn primary mt-2 mb-8">Đăng ký ngay</button>
              </form>
            <? } ?>
          </div>
        </div>
        <div class="">
          <? foreach ($units as $unit) {
            $lessons = getLessonByIdUnit($unit['idUnit']);
          ?>
            <div x-data="{ open: false }" class="w-100% mb-1">
              <button @click="open = !open" class="w-[100%] flex items-center justify-start px-6 py-4 border border-md border-[#d0d0d0] text-gray-600 hover:text-black rounded-md hover:bg-gray-100">
                <? if (count($lessons) > 0) { ?><i class="fa-solid fa-plus mr-2"></i><? } ?> <?= $unit['nameUnit'] ?>
              </button>
              <? foreach ($lessons as $lesson) { ?>
                <div x-show="open" x-transition class="flex items-center mt-1 pl-10 pr-6 py-3 bg-[#fbfbfb] border border-[#f2f2f2] rounded">
                  <i class="fa-solid fa-minus mr-2"></i> <?= $lesson['nameLesson'] ?>
                </div>
              <? } ?>
            </div>

          <? } ?>
        </div>
      </div>
    </div>
    <div class="rating bg-white mt-4 rounded-md">
      <div class="flex items-center mb-2 ml-2 pt-3 pl-2 text-[25px]">
        <p class="font-[500] mr-3">ĐÁNH GIÁ KHÓA HỌC<? if (count($ratings) <= 0) { ?>
        <p class="text-md text-gray-600 ml-1 mr-1">(Chưa có đánh giá nào)</p><? } ?></p>
      <div class="relative inline-block text-[22px]">
        <div class="text-[#ccc]">★★★★★</div>
        <div class="absolute top-0 left-0 text-[gold] overflow-hidden w-[<?= $rated / 5 * 100 ?>%] whitespace-nowrap">★★★★★</div>
      </div>
      </div>
      <div class="rating-content">
      </div>
      
      <div class="rounded-[10px] overflow-hidden">
        <? foreach ($ratings as $rating) {
          $user = getUserById($rating['idStudent']);
        ?>
          <div class="py-4 px-8 bg-white border-b border-[#ddd]">
            <div class="relative inline-block text-[16px]">
              <div class="text-[#ccc]">★★★★★</div>
              <div class="absolute top-0 left-0 text-[gold] overflow-hidden w-[<?= $rating['rated'] / 5 * 100 ?>%] whitespace-nowrap">★★★★★</div>
            </div>
            <div class="">
              <div class="flex items-center">
                <div class="mr-3 h-[40px] w-[40px] bg-center bg-no-repeat bg-cover rounded-[50%] border border-[1px] border-[#dfdfdf]" style="background-image: url('./../../<?= $user['avatar'] ?>');"></div>
                <p class="text-[#444] text-[15px]"><?= $user['username'] ?></p>
              </div>
              <div class="mt-2 ml-1"><?= $rating['content'] ?></div>
              <?if($rating['idStudent'] == $_SESSION['user_id']) {?>
                <div class="mt-2 ml-1">
                  <a href="./../../handle/rating_handle.php?action=delete&id=<?=$rating['idRating']?>" class="text-[12px] underline text-gray-500 hover:text-gray-600">Xóa</a>
                </div>
              <?}?>
            </div>
          </div>
        <? } ?>
      </div>
      <?if(!isset($ratingUserCourse) && isset($registeredCourse)) {?>
      <div class="mt-10 px-6 py-4 text-2xl font-bold">Thêm đánh giá</div>
      <div class="px-6 pb-4 flex flex-col w-full items-start">
        <div id="stars" class="flex flex-row-reverse text-lg mb-1 cursor-pointer select-none">
          <i class="fa-solid fa-star text-yellow-400" data-value="5"></i>
          <i class="fa-solid fa-star text-yellow-400" data-value="4"></i>
          <i class="fa-solid fa-star text-yellow-400" data-value="3"></i>
          <i class="fa-solid fa-star text-yellow-400" data-value="2"></i>
          <i class="fa-solid fa-star text-yellow-400" data-value="1"></i>
        </div>
        <form class="flex-1 w-[100%]" action="./../../handle/rating_handle.php" method="POST">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="rated" id="rated" value="5">
          <input type="hidden" name="by-user" value="">
          <input type="hidden" name="student" value="<?=$currentUser['idUser']?>" id="">
          <input type="hidden" name="course" value="<?=$course['idCourse']?>" id="">
          <div class="flex items-center">
            <div class="h-[40px] w-[40px] bg-center bg-no-repeat bg-cover rounded-[50%] border border-[1px] border-[#dfdfdf]" style="background-image: url('./../../<?= $currentUser['avatar'] ?>');"></div>
            <p class="text-md text-gray-600 ml-4"><?=$currentUser['username']?></p>
          </div>
          <div class="flex-1 mt-2 mb-2">
            <input name="content" class="w-full px-6 py-2 border border-[#ccc] rounded-lg" type="text">
          </div>
          <button type="submit" class="btn primary">Gửi</button>
        </form>
      </div>
      <?}?>
    </div>
  </div>
  </div>
  </div>

  <? include "./../components/footer.php"; ?>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script>
    const stars = document.querySelectorAll('#stars i');
    const ratedInput = document.getElementById('rated');

    stars.forEach(star => {
      star.addEventListener('click', () => {
        const value = parseInt(star.getAttribute('data-value'));

        ratedInput.value = value;

        // reset lại màu tất cả sao
        stars.forEach(s => {
          s.classList.remove('text-yellow-400');
          s.classList.add('text-gray-400');
        });

        for (let i = 0; i < stars.length; i++) {
          if (parseInt(stars[i].getAttribute('data-value')) <= value) {
            stars[i].classList.add('text-yellow-400');
            stars[i].classList.remove('text-gray-400');
          }
        }
      });
    });
  </script>


</body>

</html>