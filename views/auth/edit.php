<?
session_start();
require_once __DIR__ . '/../../handle/auth_handle.php';
checkLogin();
$idEdit = $_SESSION['user_id'] ?? '';
require __DIR__ . '/../../handle/user_handle.php';
require __DIR__ . '/../../handle/role_handle.php';
$user = handleGetUserById($idEdit);
$backUrl = $_SESSION['prev_url'];
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chỉnh sửa thông tin cá nhân</title>
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/students/add.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
    <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color))] flex justify-center font-[450] uppercase">Sửa thông tin cá nhân</h1>
    <form action="./../../handle/user_handle.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit" />
      <input type="hidden" name="by-user" value="edit" />
      <input type="hidden" name="id" value="<?=$idEdit?>" />

      <div class="form-input">
        <label class="label">Tên người dùng</label>
        <input required value="<?=htmlspecialchars($old['username'] ?? $user['username'])?>" type="text" name="username" class="input" placeholder="Nhập tên người dùng" value="<?= htmlspecialchars($old['username'] ?? '') ?>" />
        <? if (isset($errors['username'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['username']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input">
        <label class="label">Số điện thoại</label>
        <input required value="<?= isset($old['phone']) ? htmlspecialchars($old['phone']) : htmlspecialchars($user['phone'])?>" type="text" name="phone" class="input" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" />
        <? if (isset($errors['phone'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['phone']) ?></p>
        <? }; ?>
      </div>
      
      <div class="form-input">
        <label class="label">Avatar</label>
        <input accept="image/*" value="<?=htmlspecialchars($old['avatar'] ?? $user['avatar'] ?? '')?>" type="file" name="avatar" class="input" placeholder="Link ảnh đại diện" />
        <? if (isset($errors['avatar'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['avatar']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input action">
        <button type="submit" class="btn primary flex flex-1 items-center justify-center">
          <i class="fa-solid fa-pen-to-square"></i> Sửa
        </button>
        <a href="<?= $backUrl ?>" class="ml-[8px] btn danger flex flex-1 items-center justify-center">
          <i class="fa-solid fa-xmark text-sm"></i> Hủy
        </a>
      </div>
    </form>
  </div>
</body>

</html>