// main.js - util umum: sidebar active + restore saved theme
(function () {
    // restore theme from localStorage (if set)
    var saved = localStorage.getItem('gomeal-theme');
    if (saved) {
        document.body.classList.remove('theme-yellow', 'theme-blue');
        document.body.classList.add(saved);
    }

    // make sidebar nav clickable: add active class
    document.addEventListener('click', function (e) {
        var t = e.target;
        if (t.closest && t.closest('.user-sidebar .nav')) {
            // nav click
            var item = t.closest('.nav-item') || t;
            if (!item) return;
            document.querySelectorAll('.user-sidebar .nav .nav-item').forEach(function (i) {
                i.classList.remove('active');
            });
            item.classList.add('active');
            e.preventDefault();
        }
    });

    // small helper to show toast
    window.showToast = function (msg, timeout) {
        timeout = timeout || 1800;
        var t = document.createElement('div');
        t.className = 'g-toast';
        t.textContent = msg;
        Object.assign(t.style, {
            position: 'fixed', right: '20px', bottom: '20px', background: 'rgba(0,0,0,0.75)', color: '#fff',
            padding: '10px 14px', borderRadius: '8px', zIndex: 9999
        });
        document.body.appendChild(t);
        setTimeout(function () { t.style.opacity = 0; t.addEventListener('transitionend', () => t.remove()); }, timeout);
    };
})();