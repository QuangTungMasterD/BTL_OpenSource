
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <link rel="stylesheet" href="/BTL-N2/css/global.css">
  <link rel="stylesheet" href="/BTL-N2/css/components/sidebar-manager.css">
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body>
  <?php
  function isActiveSideManager($path)
  {
    return $_SERVER['PHP_SELF'] == $path
      ? 'text-[#fcfcfc] bg-[rgb(var(--primary-color),0.9)]'
      : 'text-[#555]';
  }
  ?>
  <div class="manager-sidebar col-span-1 bg-white sticky top-[var(--height-header)] h-[calc(100vh-var(--height-header))]">
    <ul class="pt-3">
      <?if(isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {?>
        <li>
          <a class="side-bar-items <?= isActive('/BTL-N2/views/manager/index.php@', 'text-[#555]', 'text-[#fcfcfc] bg-[rgb(var(--primary-color))]') ?>" href="/BTL-N2/views/manager/">Dashboard</a>
        </li>
      <?}?>
      <li>
        <button aria-controls="dropdown-manager-courses" data-collapse-toggle="dropdown-manager-courses" class="side-bar-items <?= isActiveSideManager('/BTL-N2/views/manager/courses/index.php') ?> <?= isActiveSideManager('/BTL-N2/views/manager/units/index.php') ?> <?= isActiveSideManager('/BTL-N2/views/manager/lessons/index.php') ?>" href="/BTL-N2/views/manager/courses/index.php">Quản lý khóa học<i class="fa-solid fa-angle-down"></i></button>
        <ul id="dropdown-manager-courses" class="hidden py-2 space-y-2">
          <li>
              <a class="side-bar-items-child <?= isActiveSideManager('/BTL-N2/views/manager/courses/index.php') ?>" href="/BTL-N2/views/manager/courses/index.php">Khóa học</a>
          </li>
          <li>
              <a class="side-bar-items-child <?= isActiveSideManager('/BTL-N2/views/manager/units/index.php') ?>" href="/BTL-N2/views/manager/units/index.php">Chương học</a>
          </li>
          <li>
              <a class="side-bar-items-child <?= isActiveSideManager('/BTL-N2/views/manager/lessons/index.php') ?>" href="/BTL-N2/views/manager/lessons/index.php">Bài học</a>
          </li>
        </ul>
      </li>
      <?if(isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {?>
        <li>
          <a class="side-bar-items <?= isActive('/BTL-N2/views/manager/users/index.php@', 'text-[#555]', 'text-[#fcfcfc] bg-[rgb(var(--primary-color))]') ?>" href="/BTL-N2/views/manager/users/index.php">Quản lý người dùng</a>
        </li>
        <li>
          <a class="side-bar-items <?= isActiveSideManager('/BTL-N2/views/manager/comments/index.php') ?>" href="/BTL-N2/views/manager/comments/index.php">Quản lý bình luận</a>
        </li>
        <li>
          <a class="side-bar-items <?= isActiveSideManager('/BTL-N2/views/manager/topics/index.php') ?>" href="/BTL-N2/views/manager/topics/index.php">Quản lý chủ đề</a>
        </li>
        <li>
          <a class="side-bar-items <?= isActiveSideManager('/BTL-N2/views/manager/registered-courses/index.php') ?>" href="/BTL-N2/views/manager/registered-courses/index.php">Quản lý đăng ký học</a>
        </li>
      <?}?>
      <li>
        <a class="side-bar-items <?= isActiveSideManager('/BTL-N2/views/manager/ratings/index.php') ?>" href="/BTL-N2/views/manager/ratings/index.php"><?if($_SESSION['role'] == 'Admin') {?>Quản lý đánh giá <?} else {?>Xem đánh giá<?}?></a>
      </li>
    </ul>
  </div>
</body>

</html>