<?
session_start();
$idEdit = $_SESSION['id'] = $_GET['id'] ?? '';
require_once __DIR__ . '/../../../handle/auth_handle.php';
checkLogin();
isTeacherLogin();
require __DIR__ . '/../../../handle/unit_handle.php';
require __DIR__ . '/../../../handle/course_handle.php';
$unit = handleGetUnitById($idEdit);
$courses = handleGetCourseByIdUser(($_SESSION['role'] == 'Admin' ? '' : $_SESSION['user_id']));

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
  <title>Chỉnh sửa chương</title>
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/students/add.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
    <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color))] flex justify-center font-[450] uppercase">Sửa chương</h1>
    <form action="./../../../handle/unit_handle.php" method="POST">
      <input type="hidden" name="action" value="edit" />
      <input type="hidden" name="id" value="<?=$idEdit?>" />

      <div class="form-input">
        <label class="label">Tên chương</label>
        <input required value="<?=htmlspecialchars($old['nameUnit'] ?? $unit['nameUnit'])?>" type="text" name="nameUnit" class="input" placeholder="Nhập tên người dùng" value="<?= htmlspecialchars($old['nameUnit'] ?? '') ?>" />
        <? if (isset($errors['nameUnit'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['nameUnit']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input">
        <label class="label">Khóa học</label>
        <select class="select" name="course" id="course">
          <? foreach($courses as $r) { ?>
            <option value="<?= $r['idCourse'] ?>" <?= ($old['course'] ?? $unit['idCourse'] ?? '') == $r['idCourse'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['nameCourse']) ?>
            </option>
          <? }; ?>
        </select>
        <? if(isset($errors['course'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['course']) ?></p>
        <? }; ?>
      </div>
      
      <div class="form-input">
        <label class="label">Thứ tự trong khóa học</label>
        <input required value="<?=htmlspecialchars($old['order'] ?? $unit['order'] ?? 0)?>" type="number" name="order" class="input" placeholder="Link ảnh đại diện" />
        <? if (isset($errors['order'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['order']) ?></p>
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