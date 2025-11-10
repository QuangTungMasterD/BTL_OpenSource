
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const registeredCourseId = this.getAttribute('data-registered-course-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/registered-course_handle.php?action=delete&id=${registeredCourseId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa đăng ký khóa học này?`
    });
  });
});
