<?
  session_start();
  require_once __DIR__ . '/../../../handle/auth_handle.php';
  require_once __DIR__ . '/../../../handle/topic_handle.php';
  checkLogin();
  isAdminLogin();
  $backUrl = $_SESSION['prev_url'] ?? '/BTL-N2/views/manager/topics/index.php';
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm chương mới</title>
<link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/add.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Thêm chủ đề mới</h1>
  <form action="./../../../handle/topic_handle.php" method="POST">
    <input type="hidden" name="action" value="add" />

    <div class="form-input">
      <label class="label">Tên chủ đề</label>
      <input name="nameTopic" class="input" placeholder="Nhập tên chủ đề" value="<?=htmlspecialchars($old['nameTopic'] ?? '') ?>">
      <? if(isset($errors['nameTopic'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['nameTopic']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Màu chủ đề</label>
      <input name="color" class="input" placeholder="Nhập màu chủ đề rga 0, 0, 0; 255, 0, 0; ..." value="<?=htmlspecialchars($old['color'] ?? '') ?>">
      <? if(isset($errors['color'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['color']) ?></p>
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