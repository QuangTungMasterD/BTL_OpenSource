document.querySelectorAll('.toast-header').forEach(toast => {
  setTimeout(() => {
    toast.classList.add('hide');
    setTimeout(() => toast.remove(), 500);
  }, 3000);
});