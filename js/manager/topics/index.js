
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const topicId = this.getAttribute('data-topic-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/topic_handle.php?action=delete&id=${topicId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa chủ đề này?`
    });
  });
});
