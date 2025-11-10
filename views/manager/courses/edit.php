<?
session_start();
$idEdit = $_SESSION['id'] = $_GET['id'] ?? '';
require_once __DIR__ . '/../../../handle/auth_handle.php';
checkLogin();
isTeacherLogin();
require_once __DIR__ . '/../../../handle/topic_handle.php';
require_once __DIR__ . '/../../../handle/course_handle.php';
require_once __DIR__ . '/../../../handle/user_handle.php';

$course = handleGetCourseById($idEdit);

$backUrl = $_SESSION['prev_url'];
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];

$teachers = handleGetUserRoleTeacher();
$topics = handleGetAllTopics();

unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chỉnh sửa khóa học</title>
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/students/add.css">
  <link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
    <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Sửa khóa học</h1>
    <form action="./../../../handle/course_handle.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit" />
      <input type="hidden" name="id" value="<?=$idEdit?>" />

      <div class="form-input">
        <label class="label">Tên khóa học</label>
        <input required value="<?=htmlspecialchars($old['nameCourse'] ?? $course['nameCourse'] ?? '')?>" type="text" name="nameCourse" class="input" placeholder="Nhập tên khóa học" />
        <? if (isset($errors['nameCourse'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['nameCourse']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input">
        <label class="label">Chi tiết khóa học</label>
        <textarea required type="text" name="descrip" class="input"  ><?=htmlspecialchars($old['descrip'] ?? $course['descrip'] ?? '')?></textarea>
        <? if (isset($errors['descrip'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['descrip']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input">
        <label class="label">Giá tiền</label>
        <input required value="<?=htmlspecialchars($old['price'] ?? $course['price'] ?? 0)?>" type="number" name="price" class="input" placeholder="Nhập giá khóa học" />
        <? if (isset($errors['price'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['price']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input">
        <label class="label">Giảm giá</label>
        <input value="<?=htmlspecialchars($old['sale'] ?? $course['sale'] ?? 0)?>" type="text" name="sale" class="input" placeholder="Nhập phần trăm giảm giá" />
        <? if (isset($errors['sale'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['sale']) ?></p>
        <? }; ?>
      </div>
      
      <div class="form-input">
        <label class="label">Ảnh đại diện</label>
        <input value="<?=htmlspecialchars($old['imgCourse'] ?? $course['imgCourse'] ?? '')?>" type="file" name="imgCourse" accept="image/*" class="input" placeholder="Nhập link ảnh đại diện" />
        <? if (isset($errors['imgCourse'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['imgCourse']) ?></p>
        <? }; ?>
      </div>

      <div class="form-input">
        <label class="label">Chọn chủ đề</label>
        <select required class="select" name="topic" id="topic">
          <? foreach ($topics as $r) { ?>
            <option value="<?= $r['idTopic'] ?>" <?= ($old['topic'] ?? $course['idTopic'] ?? '') == $r['idTopic'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['nameTopic']) ?>
            </option>
          <? }; ?>
        </select>
        <? if (isset($errors['topic'])) { ?>
          <p class="notifi text-red-600"><?= htmlspecialchars($errors['topic']) ?></p>
        <? }; ?>
      </div>
      <?if ($_SESSION['role'] == 'Admin') {?>
      <div class="form-input">
        <label class="label">Chọn giáo viên</label>
        <select required name="teacher" class="select">
          <?
            foreach($teachers as $teacher) {
          ?>
            <option value="<?=htmlspecialchars($teacher['idUser'])?>" <?= ($old['teacher'] ?? $course['idUser'] ?? '') == $teacher['idUser'] ? 'selected' : '' ?>>
              <?=htmlspecialchars($teacher['username'])?>
            </option>
          <?
            }
          ?>
        </select>
      </div>
      <?} else {?><input type="hidden" name="teacher" value="<?=$_SESSION['user_id']?>"><?}?>

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