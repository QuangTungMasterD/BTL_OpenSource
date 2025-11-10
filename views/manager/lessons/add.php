<?
  session_start();
  require_once __DIR__ . '/../../../handle/auth_handle.php';
  require_once __DIR__ . '/../../../handle/unit_handle.php';
  checkLogin();
  isTeacherLogin();
  $backUrl = $_SESSION['prev_url'] ?? '/BTL-N2/views/manager/lessons/index.php';
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);

  $units = handleGetUnitByIdUser(($_SESSION['role'] == 'Admin' ? '' : $_SESSION['user_id']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm bài mới</title>
<link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/add.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Thêm bài học mới</h1>
  <form action="./../../../handle/lesson_handle.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="add"/>

    <div class="form-input">
      <label class="label">Tên bài mới</label>
      <input type="text" name="nameLesson" class="input" placeholder="Nhập tên bài" value="<?= htmlspecialchars($old['nameLesson'] ?? '') ?>" />
      <? if(isset($errors['nameLesson'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['nameLesson']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
        <label class="label">Chi tiết bài học</label>
        <textarea type="text" name="descrip" class="input"  ><?=htmlspecialchars($old['descrip'] ?? '')?></textarea>
        <? if (isset($errors['descrip'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['descrip']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input">
        <label class="label">Video bài học</label>
        <input value="<?=htmlspecialchars($old['urlVideo'] ?? '')?>" type="file" accept="video/*" name="urlVideo" class="input" placeholder="Nhập video bài học" />
        <? if (isset($errors['urlVideo'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['urlVideo']) ?></p>
        <? }; ?>
      </div>

    <div class="form-input">
      <label class="label">Chương học</label>
      <select class="select" name="unit" id="unit">
        <? foreach($units as $r) { ?>
          <option value="<?= $r['idUnit'] ?>" <?= ($old['unit'] ?? '') == $r['idUnit'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['nameUnit']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['unit'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['unit']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Thứ tự bài trong chương học</label>
      <input type="number" name="order" value="<?= htmlspecialchars($old['order'] ?? 0) ?>" class="input" placeholder="Nhập thứ tự bài" />
      <? if(isset($errors['order'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['order']) ?></p>
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