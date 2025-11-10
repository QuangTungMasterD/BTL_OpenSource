<?php
session_start();
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
if(isset($_SESSION['user_id']) && isset($_SESSION['phone'])) {
  header('Location: ./../../index.php');
  exit;
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký</title>

  <link rel="stylesheet" href="./../../css/global.css">
  <link rel="stylesheet" href="./../../css/auth/login.css">
  <link rel="stylesheet" href="./../../css/components/header.css">

  <link rel="stylesheet" href="./../../fontawesome-free-7.1.0-web/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body>
  <?php include './../components/header.php'; ?>
  <div class="mt-[var(--height-header)] max-w-[600px] mx-auto h-[calc(100vh-var(--height-header))] relative">
    <div class="">
      <form action="./../../handle/user_handle.php" class="sign-in-form" method="POST">
        <input type="hidden" name="action" value="add">
        <div class="flex flex-col items-center justify-center mb-13 pt-5">
          <div class="text-[34px] text-[rgb(var(--primary-color))]">Đăng ký</div>
          <div class="text-md mt-2 text-gray-600">Đăng ký để truy cập khóa học và quản lý các khóa học.</div>
        </div>
        <div class="">
          <div class="form-input">
            <label for="">Họ tên</label>
            <input type="text" class="input" name="username" placeholder="Nhập họ và tên" value="<?= htmlspecialchars($old['username'] ?? '') ?>" required />
            <?php if(isset($errors['username'])) { ?>
              <p class="notifi text-red-600"><?= htmlspecialchars($errors['username']) ?></p>
            <?php }; ?>
          </div>

          <div class="form-input mt-6">
            <label for="">Số điện thoại</label>
            <input type="text" class="input" name="phone" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" required />
            <?php if(isset($errors['phone'])) { ?>
              <p class="notifi text-red-600"><?= htmlspecialchars($errors['phone']) ?></p>
            <?php }; ?>
          </div>
          <div class="form-input mt-6">
            <label for="">Mật khẩu</label>
            <input type="password" name="password" class="input" placeholder="Nhập mật khẩu" required />
            <?php if(isset($errors['password'])) { ?>
              <p class="notifi text-red-600"><?= htmlspecialchars($errors['password']) ?></p>
            <?php }; ?>
          </div>

          <div class="form-input mt-6">
            <label for="">Xác nhận mật khẩu</label>
            <input type="password" name="confirm" class="input" placeholder="Nhập lại mật khẩu" required />
            <?php if(isset($errors['confirm'])) { ?>
              <p class="notifi text-red-600"><?= htmlspecialchars($errors['confirm']) ?></p>
            <?php }; ?>
          </div>
          <button type="submit" class="btn primary w-[100%] mt-10 button-login">Đăng ký</button>
          <div class="mt-12 flex flex-col items-center justify-center">
            <div class="mt-4">Đã có tài khoản? <a href="./login.php" class="text-[rgb(var(--primary-color))] hover:underline cursor-pointer">Đăng nhập</a></div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <script src="./../../js/auth/login.js"></script>
</body>

</html>