<?
session_start();
$idEdit = $_SESSION['id'] = $_GET['id'] ?? '';
require_once __DIR__ . '/../../../handle/auth_handle.php';
checkLogin();
isTeacherLogin();
require_once __DIR__ . '/../../../handle/registered-course_handle.php';
require_once __DIR__ . '/../../../handle/user_handle.php';
require_once __DIR__ . '/../../../handle/course_handle.php';
$registeredCourse = handleGetRegisteredCourseById($idEdit);

$courses = handleGetAllCourses();
$users = handleGetAllUser();

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
  <title>Chỉnh sửa đăng ký học</title>
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/add.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<!-- not do -->
<body>
  <div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
    <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color))] flex justify-center font-[450] uppercase">Sửa đăng ký khóa học</h1>
    <form action="./../../../handle/registered-course_handle.php" method="POST">
      <input type="hidden" name="action" value="edit" />
      <input type="hidden" name="id" value="<?=$idEdit?>" />

      <div class="form-input">
      <label class="label">Khóa học đánh giá</label>
      <select class="select" name="course" id="course">
        <? foreach($courses as $r) { ?>
          <option value="<?= $r['idCourse'] ?>" <?= ($old['course'] ?? $registeredCourse['idCourse'] ?? '') == $r['idCourse'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['nameCourse']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['course'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['course']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Người đánh giá</label>
      <select class="select" name="student" id="student">
        <? foreach($users as $r) { ?>
          <option value="<?= $r['idUser'] ?>" <?= ($old['student'] ?? $registeredCourse['idStudent'] ?? '') == $r['idUser'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['username']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['student'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['student']) ?></p>
      <? }; ?>
    </div>
    
    <div class="form-input">
      <label class="label">Số tiền</label>
      <input type="number" name="costed" class="input" placeholder="Nhập số tiền đăng ký" value="<?=htmlspecialchars($old['costed'] ?? $registeredCourse['costed'] ?? 0) ?>" />
      
      <? if(isset($errors['costed'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['costed']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input action">
      <button type="submit" class="btn primary flex flex-1 items-center justify-center">
        <i class="fa-solid fa-plus text-sm"></i> Sửa
      </button>
      <a href="<?= $backUrl ?>" class="ml-[8px] btn danger flex flex-1 items-center justify-center">
        <i class="fa-solid fa-xmark text-sm"></i> Hủy
      </a>
    </div>
    </form>
  </div>
</body>

</html>