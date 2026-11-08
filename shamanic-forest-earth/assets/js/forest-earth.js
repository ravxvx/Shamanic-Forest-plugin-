/* Shamanic Forest & Earth 3.3 — review slider (no dependencies) */
(function () {
  var root = document.querySelector('.sfe-quotes');
  if (!root) return;
  var slides = root.querySelectorAll('.sfe-quote');
  var dots = root.querySelectorAll('.sfe-quotes__dots button');
  if (slides.length < 2) {
    root.querySelectorAll('.sfe-quotes__nav, .sfe-quotes__dots').forEach(function (el) { el.style.display = 'none'; });
    return;
  }
  var current = 0, timer = null;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function show(i) {
    current = (i + slides.length) % slides.length;
    slides.forEach(function (s, n) {
      var on = n === current;
      s.classList.toggle('is-active', on);
      if (on) { s.removeAttribute('hidden'); } else { s.setAttribute('hidden', ''); }
    });
    dots.forEach(function (d, n) {
      d.classList.toggle('is-active', n === current);
      d.setAttribute('aria-selected', n === current ? 'true' : 'false');
    });
  }
  function start() { if (!reduce) { stop(); timer = setInterval(function () { show(current + 1); }, 7000); } }
  function stop() { if (timer) { clearInterval(timer); timer = null; } }

  root.querySelector('.sfe-quotes__prev').addEventListener('click', function () { show(current - 1); start(); });
  root.querySelector('.sfe-quotes__next').addEventListener('click', function () { show(current + 1); start(); });
  dots.forEach(function (d, n) { d.addEventListener('click', function () { show(n); start(); }); });
  root.addEventListener('mouseenter', stop);
  root.addEventListener('mouseleave', start);
  root.addEventListener('focusin', stop);
  start();
})();
