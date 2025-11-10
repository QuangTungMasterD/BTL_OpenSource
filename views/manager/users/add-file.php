<?
  session_start();
  require_once __DIR__ . '/../../../handle/auth_handle.php';
  checkLogin();
  isAdminLogin();
  $backUrl = $_SESSION['prev_url'] ?? '/BTL-N2/views/manager/users/index.php';
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);
  require_once __DIR__ . '/../../../handle/role_handle.php';

  $_GET['file'] = $_GET['file'] ?? '';
  $typeFile = $_GET['file'] == 'excel' || $_GET['file'] == 'csv' ? $_GET['file'] : 'excel';

  $roles = handleGetAllRoles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nhập file người dùng</title>
<link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/students/add.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Nhập file người dùng</h1>
  <form action="./../../../handle/user_handle.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add-file" />
    <input type="hidden" name="file" value="<?=$typeFile?>" />

    <div class="form-input">
      <label class="label">Chọn file dữ liệu</label>
      <input type="file" name="file-users" class="input" value="<?= htmlspecialchars($old['file-users'] ?? '') ?>" />
      <? if(isset($errors['file-users'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['file-users']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input action">
      <button type="submit" class="btn primary flex flex-1 items-center justify-center">
        <i class="fa-solid fa-plus text-sm"></i> Thêm
      </button>
      <a href="<?= $backUrl ?>" class="ml-[8px] btn danger flex flex-1 items-center justify-center">
        <i class="fa-solid fa-xmark text-sm"></i> Hủy
      </a>
    </div>
  </form>
</div>
</body>
</html>