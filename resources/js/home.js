document.addEventListener('DOMContentLoaded', function () {

    /* ------------------------------------------------------------
       1. SCROLL REVEAL (respects prefers-reduced-motion)
    ------------------------------------------------------------ */
    var prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var revealTargets = document.querySelectorAll('[data-reveal], [data-reveal-item]');

    if (prefersReducedMotion) {
        revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
    } else if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry, index) {
                if (entry.isIntersecting) {
                    setTimeout(function () {
                        entry.target.classList.add('is-visible');
                    }, index * 60);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        revealTargets.forEach(function (el) { observer.observe(el); });
    } else {
        revealTargets.forEach(function (el) { el.classList.add('is-visible'); });
    }

    /* ------------------------------------------------------------
       2. COUNTUP FOR LIVE STATISTICS (database-driven targets)
       Works with the existing x-stats component's
       <span class="stat-number" data-target="..."> markup
    ------------------------------------------------------------ */
    var statNumbers = document.querySelectorAll('[data-target]');
    if (statNumbers.length && window.countUp) {
        var CountUp = window.countUp.CountUp || window.countUp;

        var statsObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var el = entry.target;
                    var target = parseInt(el.getAttribute('data-target'), 10) || 0;
                    var suffix = el.getAttribute('data-suffix') || '';
                    var counter = new CountUp(el, target, {
                        duration: 2,
                        separator: ',',
                        suffix: suffix,
                    });
                    if (!counter.error) {
                        counter.start();
                    } else {
                        el.textContent = target.toLocaleString() + suffix;
                    }
                    statsObserver.unobserve(el);
                }
            });
        }, { threshold: 0.4 });

        statNumbers.forEach(function (el) { statsObserver.observe(el); });
    }

});