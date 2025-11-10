<?
  session_start();
  $_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];

  require_once 'handle/course_handle.php';

  $courses = handleGetAllCoursesRenderHome();
  $total = count($courses) > 10 ? 10 : count($courses);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TDEDU</title>
  <link rel="stylesheet" href="./css/global.css">
  <link rel="stylesheet" href="./css/index.css">
  <link rel="stylesheet" href="./fontawesome-free-7.1.0-web/css/all.min.css">

  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <? include "./views/components/header.php"; ?>
  <div class="mt-[calc(var(--height-header))] mx-auto bg-white rounded-md">
    <div class="carousel">
      <div class="carousel-list" id="carouselList">
        <!-- Slider items -->
        <div class="carousel-item " style="background-image: url('https://congnghethongtinaau.com/wp-content/uploads/2024/11/code-la-mot-mang-kien-thuc-quan-trong.jpg');">
          <div class="content w-[400px] ml-[20px] md:ml-[50px] md:w-[600px] lg:w-[900px] lg:ml-[100px]">
            <div class="title lg:text-[100px] md:text-[60px] text-[26px]">Javascript</div>
            <div class="name lg:text-[80px] md:text-[50px] text-[20px]">Javascript cơ bản</div>
            <div class="desc">Khóa học Javascript cơ bản dành cho người mới bắt đầu</div>
            <!-- <a href="https://www.youtube.com/watch?v=Ijk7EvPa0Vw" class="btn primary">Xem ngay</a> -->
          </div>
        </div>

        <div class="carousel-item " style="background-image: url('https://trungquandev.com/wp-content/uploads/2018/04/tong-quan-nodejs-trungquandev-02.jpg');">
          <div class="content w-[400px] ml-[20px] md:ml-[50px] md:w-[600px] lg:w-[900px] lg:ml-[100px]">
            <div class="title lg:text-[100px] md:text-[60px] text-[26px]">Nodejs</div>
            <div class="name lg:text-[80px] md:text-[50px] text-[20px]">Nodejs - ExpressJs</div>
            <div class="desc">Khóa học lập trình nodeJs/expressJs từ cơ bản đến nâng cao</div>
            <!-- <a href="https://www.youtube.com/watch?v=Ijk7EvPa0Vw" class="btn primary">Xem ngay</a> -->
          </div>
        </div>
        
        <div class="carousel-item " style="background-image: url('https://vtiacademy.edu.vn/upload/images/anh-seo/2/kien-thuc-can-nam-khi-hoc-lap-trinh-c-co-ban.jpg');">
          <div class="content w-[400px] ml-[20px] md:ml-[50px] md:w-[600px] lg:w-[900px] lg:ml-[100px]">
            <div class="title lg:text-[100px] md:text-[60px] text-[26px]">C++</div>
            <div class="name lg:text-[80px] md:text-[50px] text-[20px]">C++ cơ bản</div>
            <div class="desc">Khóa học lập trình cơ bản cho người mời bắt đầu</div>
            <!-- <a href="https://www.youtube.com/watch?v=Ijk7EvPa0Vw" class="btn primary">Xem ngay</a> -->
          </div>
        </div>
      </div>

      <div class="arrows bottom-[10px]">
        <button id="prev" class="prev"></button>
        <ul class="flex">
          <li class="nav-slide w-[20px] h-[6px] rounded-[6px] bg-white w-[36px]"></li>
          <li class="nav-slide w-[20px] h-[6px] rounded-[6px] bg-gray-500"></li>
          <li class="nav-slide w-[20px] h-[6px] rounded-[6px] bg-gray-500"></li>
        </ul>
        <button id="next" class="next"></button>
      </div>
    </div>
  </div>

  <div class="w-[1400px] mx-auto mt-3 p-4 rounded-md ">
    <div class="text-3xl font-bold text-gray-600">Các khóa học nổi bật</div>
    <div class="mt-3 grid grid-cols-5 gap-3">
      
      <?for($i = 0; $i < $total; $i++) {?>
        
        <a href="./views/course/index.php?id=<?=$courses[$i]['idCourse']?>" class="cart rounded-lg relative top-0 hover:top-[-4px]">
            <div class="card-img" style="background-image: url('<?= htmlspecialchars($courses[$i]['imgCourse']) ?>');"></div>
      
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

  <? include "./views/components/footer.php"; ?>
  <script src="./js/index.js"></script>
</body>

</html>