<?
session_start();

$error = $_SESSION['errors'] ?? '';
unset($_SESSION['errors']);
if(isset($_SESSION['user_id']) && isset($_SESSION['phone'])) {
  header('Location: ' . $_SESSION['prev_url']);
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập</title>

  <link rel="stylesheet" href="./../../css/global.css">
  <link rel="stylesheet" href="./../../css/auth/login.css">
  <link rel="stylesheet" href="./../../css/components/header.css">

  <link rel="stylesheet" href="./../../fontawesome-free-7.1.0-web/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body>
  <? include './../components/header.php'; ?>
  <div class="mt-[var(--height-header)] max-w-[600px] mx-auto h-[calc(100vh-var(--height-header))] relative">
    <div class="">
      <form action="./../../handle/auth_handle.php" class="sign-in-form" method="POST">
        <input type="hidden" name="login">
        <div class="flex flex-col items-center justify-center mb-13 pt-5">
          <div class="text-[34px] text-[rgb(var(--primary-color))]">Đăng nhập</div>
          <div class="text-md mt-2 text-gray-600">Đăng nhập để truy cập khóa học và quản lý các khóa học.</div>
        </div>
        <div class="">
          <div class="form-input">
            <label for="">Số điện thoại</label>
            <input type="text" class="input" name="phone" placeholder="Nhập số điện thoại" />
            <? if (isset($error)) { ?>
              <p class="message ml-5 mt-1 text-red-600">
                <?= $error ?>
              </p>
            <? } ?>
          </div>
          <div class="form-input mt-6">
            <label for="">Mật khẩu</label>
            <input type="password" name="password" class="input" placeholder="Nhập mật khẩu" />
            <? if (isset($error)) { ?>
              <p class="message ml-5 mt-1 text-red-600">
                <?= $error ?>
              </p>
            <? } ?>
          </div>
          <button type="submit" class="btn primary w-[100%] mt-10 button-login">Đăng nhập</button>
          <div class="mt-12 flex flex-col items-center justify-center">
            <div class="text-[rgb(var(--primary-color))] hover:underline cursor-pointer">Quên mật khẩu</div>
            <div class="mt-4">Chưa có tài khoản? <a href="" class="text-[rgb(var(--primary-color))] hover:underline cursor-pointer">Đăng ký</a></div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="./../../js/auth/login.js"></script>
</body>

</html>