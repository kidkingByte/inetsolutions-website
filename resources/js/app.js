import Alpine from 'alpinejs';
import { speedTest } from './speed-test';

window.Alpine = Alpine;

Alpine.data('speedTest', speedTest);

Alpine.start();

// Grids marked [data-stagger] reveal their children one after another.
document.querySelectorAll('[data-stagger]').forEach((group) => {
    const step = Number(group.dataset.stagger) || 80;
    [...group.children].forEach((child, i) => {
        child.classList.add('reveal');
        child.style.transitionDelay = `${i * step}ms`;
    });
});

// Scroll reveal for .reveal (fade/rise) and .reveal-x (lines that draw across)
const revealTargets = document.querySelectorAll('.reveal, .reveal-x');
const show = (el) => {
    el.classList.add('is-visible');
    // Drop the stagger delay once revealed so hover effects respond instantly.
    if (el.style.transitionDelay) {
        el.addEventListener('transitionend', () => (el.style.transitionDelay = ''), { once: true });
    }
};
if (revealTargets.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                show(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px' });
    revealTargets.forEach((el) => observer.observe(el));
} else {
    revealTargets.forEach(show);
}
