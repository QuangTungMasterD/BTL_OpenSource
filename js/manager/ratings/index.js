
document.addEventListener('DOMContentLoaded', function () {
  const openButtons = document.querySelectorAll('.open-delete-modal');
  const confirmDeleteBtn = document.getElementById('confirm-delete');
  const contenModal = document.getElementById('content-modal-delete');

  openButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const ratingId = this.getAttribute('data-rating-id');
      confirmDeleteBtn.setAttribute('href', `/BTL-N2/handle/rating_handle.php?action=delete&id=${ratingId}`);
      contenModal.innerText = `Bạn có chắc chắn muốn xóa đánh giá này?`
    });
  });
});
