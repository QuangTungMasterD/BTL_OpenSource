<?
  session_start();
  require_once __DIR__ . '/../../../handle/auth_handle.php';
  require_once __DIR__ . '/../../../handle/lesson_handle.php';
  require_once __DIR__ . '/../../../handle/comment_handle.php';
  require_once __DIR__ . '/../../../handle/registered-course_handle.php';
  checkLogin();
  isAdminLogin();
  $backUrl = $_SESSION['prev_url'] ?? '/BTL-N2/views/manager/comments/index.php';
  $errors = $_SESSION['errors'] ?? [];
  $old = $_SESSION['old'] ?? [];
  unset($_SESSION['errors'], $_SESSION['old']);

  $lessons = handleGetAllLesson();
  
  $comments = handleGetAllComment();
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
  <h1 class="text-3xl mb-6 text-[rgb(var(--primary-color)] flex justify-center font-[450] uppercase">Thêm bình luận mới</h1>
  <form action="./../../../handle/comment_handle.php" method="POST">
    <input type="hidden" name="action" value="add" />

    <div class="form-input">
      <label class="label">Nội dung bình luận</label>
      <textarea name="content" class="input" placeholder="Nhập nội dung bình luận" ><?=htmlspecialchars($old['content'] ?? '') ?></textarea>
      <? if(isset($errors['content'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['content']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Bài học</label>
      <select class="select" name="lesson" id="lesson">
        <? foreach($lessons as $r) { ?>
          <option value="<?= $r['idLesson'] ?>" <?= ($old['lesson'] ?? '') == $r['idLesson'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['nameLesson']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['lesson'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['lesson']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Người dùng</label>
      <select class="select" name="user" id="user">
        <? foreach($users as $r) { ?>
          <option value="<?= $r['idUser'] ?>" <?= ($old['user'] ?? '') == $r['idUser'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['username']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['user'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['user']) ?></p>
      <? }; ?>
    </div>

    <div class="form-input">
      <label class="label">Bình luận cha</label>
      <select class="select" name="parentComment" id="parentComment">
        <option value="">
          Không
        </option>
        <? foreach($comments as $r) { ?>
          <option value="<?= $r['idComment'] ?>" <?= ($old['parentComment'] ?? '') == $r['idComment'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['idComment']) ?>
          </option>
        <? }; ?>
      </select>
      <? if(isset($errors['parentComment'])) { ?>
        <p class="notifi text-red-600"><?= htmlspecialchars($errors['parentComment']) ?></p>
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
const selectLessonElement = document.getElementById('lesson');
const selectUserElement = document.getElementById('user');

function handleGetUserByLesson() {
  const idLesson = selectLessonElement.value;

  fetch(`./../../../handle/registered-course_handle.php?action=get-users-registered&idLesson=${idLesson}`, {
    method: "GET",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
  })
  .then(res => res.json())
  .then(data => {
    html = '';
    
    data.forEach(element => {
      html += `
        <option value="${element['idUser']}" ${element['idUser'] == '<?= ($old['user'] ?? '')?>' ? 'selected' : '' }>
          ${element['username']}
        </option>
      `;
    });
    selectUserElement.innerHTML = html;
  })
}

handleGetUserByLesson();
selectLessonElement.onchange = handleGetUserByLesson;
</script>
</body>
</html>