
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const courseId = this.getAttribute('data-course-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/course_handle.php?action=delete&id=${courseId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa khóa học này?`
    });
  });
});
