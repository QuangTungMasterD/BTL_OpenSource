
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const userId = this.getAttribute('data-user-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/user_handle.php?action=delete&id=${userId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa người dùng này?`
    });
  });
});
