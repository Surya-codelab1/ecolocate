document.addEventListener('DOMContentLoaded', function () {
    const counters = document.querySelectorAll('.counter');

    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const duration = 1200;
        const stepTime = 20;
        const steps = duration / stepTime;
        const increment = target / steps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.innerText = target.toLocaleString();
                clearInterval(timer);
            } else {
                counter.innerText = Math.floor(current).toLocaleString();
            }
        }, stepTime);
    });
});