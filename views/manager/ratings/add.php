<?
  session_start();
  require_once __DIR__ . '/../../../handle/auth_handle.php';
  checkLogin();
  isAdminLogin();
  require_once __DIR__ . '/../../../handle/rating_handle.php';

  require_once __DIR__ . '/../../../handle/registered-course_handle.php';

  $CourseRegisteredCourse = handleGetAllCourseRegisteredCourse();

  $registeredCourses = handleGetAllRegisteredCourse();

  $backUrl = $_SESSION['prev_url'] ?? '/BTL-N2/views/manager/ratings/index.php';
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thêm đánh giá mới</title>
<link rel="stylesheet" href="http://localhost/BTL-N2/css/global.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/css/manager/add.css">
<link rel="stylesheet" href="http://localhost/BTL-N2/fontawesome-free-7.1.0-web/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>
<div class="max-w-[600px] overflow-hidden mx-auto bg-white p-6 mt-12 rounded-xl mb-12">
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Thêm đánh giá mới</h1>
  <form action="./../../../handle/rating_handle.php" method="POST">
    <input type="hidden" name="action" value="add" />

    <div class="form-input">
      <label class="label">Khóa học đánh giá</label>
      <select class="select" name="course" id="course">
        <? foreach($CourseRegisteredCourse as $r) { ?>
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
      <label class="label">Người đánh giá</label>
      <select class="select" name="student" id="student">
        
      </select>
      <? if(isset($errors['student'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['student']) ?></p>
      <? }; ?>
    </div>
    
    <div class="form-input">
      <label class="label">Số sao</label>
      <input type="number" name="rated" class="input" placeholder="Nhập số sao > 0 & < 5" value="<?=htmlspecialchars($old['rated'] ?? 5) ?>" />
      <? if(isset($errors['rated'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['rated']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Nội dung đánh giá</label>
      <textarea name="content" class="input" placeholder="Nhập nội dung đánh giá"><?=htmlspecialchars($old['content'] ?? '') ?></textarea>
      <? if(isset($errors['content'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['content']) ?></p>
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
<script>
const selectCourseElement = document.getElementById('course');
const selectStudentElement = document.getElementById('student');

function handleGetUsersRegisteredCourse() {
  const valueCourse = selectCourseElement.value;
  fetch(`./../../../handle/registered-course_handle.php?action=get-users-registered&idCourse=${valueCourse}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  })
  .then(res => res.json())
  .then(data => {
    html = '';
    console.log(data, 1);
    data.forEach(element => {
      html += `
        <option value="${element['idUser']}" ${element['idUser'] == '<?= ($old['student'] ?? '')?>' ? 'selected' : '' }>
          ${element['username']}
        </option>
      `;
    });
    selectStudentElement.innerHTML = html;
  })
}
handleGetUsersRegisteredCourse();
selectCourseElement.onchange = handleGetUsersRegisteredCourse;
</script>
</body>
</html>