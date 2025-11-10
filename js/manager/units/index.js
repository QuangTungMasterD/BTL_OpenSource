
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const unitId = this.getAttribute('data-unit-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/unit_handle.php?action=delete&id=${unitId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa chương này?`
    });
  });
});
