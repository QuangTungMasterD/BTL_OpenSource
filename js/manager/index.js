const counters = document.querySelectorAll('.counter');
const duration = 1500;

counters.forEach(counter => {
  const target = +counter.getAttribute('data-target');
  const startTime = performance.now();

  const update = (currentTime) => {
    const elapsed = currentTime - startTime;
    const progress = Math.min(elapsed / duration, 1);

    counter.innerText = Math.floor(progress * target);

    if (progress < 1) {
      requestAnimationFrame(update);
    } else {
      counter.innerText = target;
    }
  };

  requestAnimationFrame(update);
});
