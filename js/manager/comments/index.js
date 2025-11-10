
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const commentId = this.getAttribute('data-comment-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/comment_handle.php?action=delete&id=${commentId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa bình luận này?`
    });
  });
});
