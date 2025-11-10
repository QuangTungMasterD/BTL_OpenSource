const list = document.querySelector('.carousel-list');
const items = document.querySelectorAll('.carousel-item');
const nextBtn = document.querySelector('.next');
const prevBtn = document.querySelector('.prev');
const dots = document.querySelectorAll('.nav-slide');

let curSlide = 0;
const timeAutoNext = 7000;
let autoSlide;

function showSlider(type) {
  clearInterval(autoSlide);
  autoSlide = setInterval(() => showSlider('next'), timeAutoNext);

  const items = list.querySelectorAll('.carousel-item');
  if (type === 'next') {
    list.appendChild(items[0]);
    curSlide = (curSlide + 1) % items.length;
  } else {
    list.prepend(items[items.length - 1]);
    curSlide = (curSlide - 1 + items.length) % items.length;
  }

  dots.forEach((dot, i) => {
    dot.classList.toggle('bg-white', i === curSlide);
    dot.classList.toggle('bg-gray-500', i !== curSlide);
    dot.classList.toggle('w-[36px]', i === curSlide);
    dot.classList.toggle('w-[20px]', i !== curSlide);
  });
}

nextBtn.addEventListener('click', () => showSlider('next'));
prevBtn.addEventListener('click', () => showSlider('prev'));
dots.forEach((dot, i) => {
  dot.addEventListener('click', () => {
    while (curSlide !== i) showSlider('next');
  });
});

autoSlide = setInterval(() => showSlider('next'), timeAutoNext);
