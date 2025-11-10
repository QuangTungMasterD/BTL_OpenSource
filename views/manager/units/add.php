<?
  session_start();
  require_once __DIR__ . '/../../../handle/auth_handle.php';
  require_once __DIR__ . '/../../../handle/course_handle.php';
  checkLogin();
  isTeacherLogin();
  $backUrl = $_SESSION['prev_url'] ?? '/BTL-N2/views/manager/units/index.php';
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);

  $courses = handleGetCourseByIdUser(($_SESSION['role'] == 'Admin' ? '' : $_SESSION['user_id']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm chương mới</title>
<link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/students/add.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Thêm chương mới</h1>
  <form action="./../../../handle/unit_handle.php" method="POST">
    <input type="hidden" name="action" value="add" />

    <div class="form-input">
      <label class="label">Tên chương mới</label>
      <input type="text" name="nameUnit" class="input" placeholder="Nhập tên chương" value="<?= htmlspecialchars($old['nameUnit'] ?? '') ?>" />
      <? if(isset($errors['nameUnit'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['nameUnit']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Khóa học</label>
      <select class="select" name="course" id="course">
        <? foreach($courses as $r) { ?>
          <option value="<?= $r['idCourse'] ?>" <?= ($old['course'] ?? '') == $r['idCourse'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['nameCourse']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['course'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['course']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Thứ tự chương trong khóa học</label>
      <input type="number" name="order" value="<?= htmlspecialchars($old['order'] ?? 0) ?>" class="input" placeholder="Nhập thứ tự chương" />
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