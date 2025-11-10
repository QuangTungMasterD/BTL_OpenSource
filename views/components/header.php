<?
require_once __DIR__ . '/../../handle/auth_handle.php';
$notifi = $_SESSION['notifi'] ?? null;
$isSuccess = $_SESSION['isSuccessNotify'] ?? null;
unset($_SESSION['notifi'], $_SESSION['isSuccessNotify']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/BTL-N2/css/global.css">
  <link rel="stylesheet" href="/BTL-N2/css/components/header.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body>
  <? if ($notifi !== null && $isSuccess !== null) { ?>
    <? if (!$isSuccess) { ?>
      <div class="fixed top-[calc(var(--height-header)+10px)] right-3 z-9999">
        <div id="toast-warning" class="toast-header flex items-center w-full max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
          <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg dark:bg-orange-700 dark:text-orange-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z" />
            </svg>
            <span class="sr-only">Warning icon</span>
          </div>
          <div class="ms-3 text-sm font-normal"><?= $notifi ?></div>
          <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-warning" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
          </button>
        </div>
      </div>
    <? } else if ($isSuccess) { ?>
      <div class="fixed top-[calc(var(--height-header)+10px)] right-3 z-9999">
        <div id="toast-success" class="toast-header flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow-sm dark:text-gray-400 dark:bg-gray-800" role="alert">
          <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
            </svg>
            <span class="sr-only">Check icon</span>
          </div>
          <div class="ms-3 text-sm font-normal"><?= $notifi ?></div>
          <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" data-dismiss-target="#toast-success" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
          </button>
        </div>
      </div>
    <? } ?>
  <? } ?>
  <?
  function isActive($pattern, $classBase, $clasActive)
  {
    $current = $_SERVER['PHP_SELF'];

    if (str_contains($pattern, '@')) {
      $base = strstr($pattern, '@', true);
      return str_starts_with($current, $base)
        ?  $clasActive
        : $classBase;
    }

    return ($current === $pattern)
      ?  $classBase
      : $clasActive;
  }
  ?>

  <header class="header px-4 flex items-center block z-50 top-0 fixed h-[var(--height-header)] bg-white w-[100%]">
    <a class="img-logo" href="/BTL-N2" style="background-image: url('http://localhost/BTL-N2/docs/logo/logo.png')"></a>

    <div class="nav">
      <ul class="flex">
        <li>
          <a class="nav-item rounded-lg block py-3 px-5 <?= isActive('/BTL-N2/index.php@', 'bg-[#fff] text-[#454545] hover:bg-[#f3f3f3] hover:text-black', 'bg-[#f3f3f3] text-black') ?>" href="/BTL-N2">Trang chủ</a>
        </li>
        <li>
          <a class="nav-item rounded-lg block py-3 px-5 <?= isActive('/BTL-N2/views/courses/index.php@', 'bg-[#fff] text-[#454545] hover:bg-[#f3f3f3] hover:text-black', 'bg-[#f3f3f3] text-black') ?>" href="/BTL-N2/views/courses/index.php">Khóa học</a>
        </li>
        <li>
          <? if (isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') { ?>
            <a class="nav-item rounded-lg block py-3 px-5 <?= isActive('/BTL-N2/views/manager@', 'bg-[#fff] text-[#454545] hover:bg-[#f3f3f3] hover:text-black', 'bg-[#f3f3f3] text-black') ?>" href="/BTL-N2/views/manager">Quản lý</a>
          <? } else if (isset($_SESSION['role']) && $_SESSION['role'] == 'Teacher') {?>
            <a class="nav-item rounded-lg block py-3 px-5 <?= isActive('/BTL-N2/views/manager@', 'bg-[#fff] text-[#454545] hover:bg-[#f3f3f3] hover:text-black', 'bg-[#f3f3f3] text-black') ?>" href="/BTL-N2/views/manager/courses/index.php">Quản lý</a>
          <?}?>
        </li>
      </ul>
    </div>

    <div class="action flex flex-1 justify-end">
      <? if (!isLoggedIn()) { ?>
        <a href="/BTL-N2/views/auth/register.php" class="flex items-center btn ghost cursor-pointer">Đăng ký</a>
        <a href="/BTL-N2/views/auth/login.php" class="flex items-center btn primary rounded ml-6">Đăng nhập</a>
      <? } else { ?>
        <!-- <button class="text-[15px] font-[500] hover:text-black flex items-center justify-center text-[#444]">
          Đang học
        </button> -->
        <!-- <button class="action-items w-[44px] h-[44px] rounded-[50%] flex items-center justify-center text-xl text-[#444]">
          <i class="fa-solid fa-bell"></i>
        </button> -->
        <button>
          <div class="action-user-login relative action-items bg-center bg-no-repeat bg-cover w-[44px] h-[44px] rounded-[50%] border border-[1px] border-[#dfdfdf]" style="background-image: url('/BTL-N2/<?= $_SESSION['imgUser']?>');">
            <ul class="absolute top-[calc(100%+10px)] rounded-md right-0 bg-white action-user">
              <li class="">
                <a href="/BTL-N2/views/auth/index.php?id=<?=$_SESSION['user_id']?>" class="text-gray-600 hover:text-black pr-8 pl-5 min-w-[200px] flex justify-start">
                  Tài khoản
                </a>
              </li>
              <li class="">
                <a href="/BTL-N2/handle/auth_handle.php?logout" class="text-red-500 pr-8 pl-5 min-w-[200px] flex justify-start">
                  Logout
                </a>
              </li>
            </ul>
          </div>
        </button>
      <? } ?>
    </div>
  </header>
  <script src="/BTL-N2/js/components/header.js"></script>
</body>

</html>