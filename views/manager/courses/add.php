<?
  session_start();
  require_once __DIR__ . '/../../../handle/auth_handle.php';
  require_once __DIR__ . '/../../../handle/user_handle.php';
  require_once __DIR__ . '/../../../handle/topic_handle.php';
  checkLogin();
  isTeacherLogin();
  $backUrl = $_SESSION['prev_url'] ?? '/BTL-N2/views/manager/courses/index.php';
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);

  $teachers = handleGetUserRoleTeacher();
  $topics = handleGetAllTopics();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm khóa học</title>
<link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/courses/add.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Thêm khóa học</h1>
  <form action="./../../../handle/course_handle.php" method="POST">
    <input type="hidden" name="action" value="add" />

    <div class="form-input">
      <label class="label">Tên khóa học<caption></caption></label>
      <input type="text" name="nameCourse" class="input" placeholder="Nhập tên khóa học" value="<?= htmlspecialchars($old['nameCourse'] ?? '') ?>" />
      <? if(isset($errors['nameCourse'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['nameCourse']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Chi tiết khóa học</label>
      <textarea type="text" name="descripCourse" class="input" placeholder="Nhập Chi tiết khóa học" ><?= htmlspecialchars($old['descripCourse'] ?? '') ?></textarea>
      <? if(isset($errors['descripCourse'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['descripCourse']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Giá</label>
      <input type="number" name="price" value="<?= htmlspecialchars($old['price'] ?? 0) ?>" class="input" placeholder="Nhập giá khóa học" />
      <? if(isset($errors['price'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['price']) ?></p>
      <? }; ?>
    </div>
        
    <div class="form-input">
      <label class="label">Chọn chủ đề</label>
      <select name="topic" class="select">
        <?
          foreach($topics as $topic) {
        ?>
          <option value="<?=htmlspecialchars($topic['idTopic'])?>" <?= ($old['topic'] ?? '') == $topic['idTopic'] ? 'selected' : '' ?>>
            <?=htmlspecialchars($topic['nameTopic'])?>
          </option>
        <?
          }
        ?>
      </select>
    </div>
    <?if ($_SESSION['role'] == 'Admin') {?>
      <div class="form-input">
        <label class="label">Chọn giáo viên</label>
        <select name="teacher" class="select">
          <?
            foreach($teachers as $teacher) {
          ?>
            <option value="<?=htmlspecialchars($teacher['idUser'])?>" <?= ($old['teacher'] ?? '') == $teacher['idUser'] ? 'selected' : '' ?>>
              <?=htmlspecialchars($teacher['username'])?>
            </option>
          <?
            }
          ?>
        </select>
      </div>
    <?} else {?><input type="hidden" name="teacher" value="<?=$_SESSION['user_id']?>"><?}?>

    <!--  -->

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