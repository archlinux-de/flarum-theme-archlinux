import app from 'flarum/forum/app';

app.initializers.add('archlinux-de/flarum-theme-archlinux', () => {
  document.addEventListener('click', (event: MouseEvent) => {
    if (!(event.target instanceof Element)) {
      return;
    }

    const toggle = event.target.closest<HTMLButtonElement>('#arch-navbar-toggle');

    if (!toggle) {
      return;
    }

    const menu = document.getElementById(toggle.getAttribute('aria-controls') ?? '');

    if (!menu) {
      return;
    }

    const open = !menu.classList.contains('is-open');
    menu.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', String(open));
  });
});
