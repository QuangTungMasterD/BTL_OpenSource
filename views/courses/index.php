<?
  session_start();
  $_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];

  require_once __DIR__ . '/../../handle/course_handle.php';

  $courses = handleGetAllCoursesRenderHome();
  $total = count($courses);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Các khóa học</title>
  <link rel="stylesheet" href="./../../css/global.css">
  <link rel="stylesheet" href="./../../css/index.css">
  <link rel="stylesheet" href="./../../fontawesome-free-7.1.0-web/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <? include "./../../views/components/header.php"; ?>
  <div class="mt-[calc(var(--height-header))] mx-auto bg-white rounded-md">

  <div class="w-[1400px] mx-auto mt-3 p-4 rounded-md ">
    <!-- <div class="text-3xl font-bold text-gray-600">Các khóa học</div> -->
    <div class="mt-3 grid grid-cols-5 gap-3">
      
      <?for($i = 0; $i < $total; $i++) {?>
        
        <a href="./../course/index.php?id=<?=$courses[$i]['idCourse']?>" class="cart rounded-lg relative top-0 hover:top-[-4px]">
            <div class="card-img" style="background-image: url('./../../<?= htmlspecialchars($courses[$i]['imgCourse']) ?>');"></div>
      
            <div class="card-content p-4">
              <div class="card-name text-2xl"><?= htmlspecialchars($courses[$i]['nameCourse']) ?></div>
              <div class="card-cost flex items-center mt-2">
                <?if($courses[$i]['sale'] > 0 && $courses[$i]['price'] > 0) {?>
                  <div class="card-price mr-2 line-through text-gray-500">
                    <?= htmlspecialchars((($courses[$i]['price'] == 0) ? 'Miễn phí' : $courses[$i]['price'].'đ')) ?>
                  </div>
                  <div class="card-costed text-[rgb(var(--primary-color))] font-bold text-lg">
                    <?= htmlspecialchars((($courses[$i]['price'] == 0) ? 'Miễn phí' : ($courses[$i]['price'] * (100 - $courses[$i]['sale']) / 100).'đ')) ?>
                  </div>
                <?} else if($courses[$i]['sale'] == 0) {?>
                  <div class="card-costed ml-0 text-[rgb(var(--primary-color))] font-bold text-lg">
                    <?= htmlspecialchars((($courses[$i]['price'] == 0) ? 'Miễn phí' : ($courses[$i]['price']).'đ')) ?>
                  </div>
                <?}?>
              </div>
              <div class="more-info flex justify-between items-center pt-5">
                <div class="rating">
                  <div class="back-stars text-[15px]">★★★★★</div>
                  <div class="front-stars text-[15px] w-[<?=(((int)$courses[$i]['totalRated'] / ((int)$courses[$i]['quantityRated'] > 0 ? (int)$courses[$i]['quantityRated'] : 1)) / 5 * 100)?>%]">★★★★★</div>
                </div>
                <div class="author text-md text-gray-600 rounded-lg"><?= htmlspecialchars($courses[$i]['username']) ?></div>
              </div>
            </div>
          </a>
      <?}?>
    </div>
  </div>

  <? include "./../../views/components/footer.php"; ?>
</body>

</html>