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

  $roles = handleGetAllRoles();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm người dùng</title>
<link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/students/add.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Thêm người dùng</h1>
  <form action="./../../../handle/user_handle.php" method="POST">
    <input type="hidden" name="action" value="add" />

    <div class="form-input">
      <label class="label">Tên người dùng</label>
      <input required type="text" name="username" class="input" placeholder="Nhập tên người dùng" value="<?= htmlspecialchars($old['username'] ?? '') ?>" />
      <? if(isset($errors['username'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['username']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Số điện thoại</label>
      <input required type="text" name="phone" class="input" placeholder="Nhập số điện thoại" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" />
      <? if(isset($errors['phone'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['phone']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Mật khẩu</label>
      <input required type="password" name="password" value="<?= htmlspecialchars($old['password'] ?? '') ?>" class="input" placeholder="Nhập mật khẩu" />
      <? if(isset($errors['password'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['password']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Xác nhận mật khẩu</label>
      <input required type="password" name="confirmpassword" value="<?= htmlspecialchars($old['confirm'] ?? '') ?>" class="input" placeholder="Nhập lại mật khẩu" />
      <? if(isset($errors['confirm'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['confirm']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Phân quyền</label>
      <select required class="select" name="role" id="role">
        <? foreach($roles as $r) { ?>
          <?if($r['nameRole'] == 'Admin' ) continue;?>
          <option value="<?= $r['idRole'] ?>" <?= ($old['role'] ?? '') == $r['idRole'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['nameRole']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['role'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['role']) ?></p>
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