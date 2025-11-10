
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const lessonId = this.getAttribute('data-lesson-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/lesson_handle.php?action=delete&id=${lessonId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa bài học này?`
    });
  });
});
