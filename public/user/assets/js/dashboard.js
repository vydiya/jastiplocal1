// dashboard.js - interaksi khusus halaman dashboard
document.addEventListener('DOMContentLoaded', function () {

    // THEME TOGGLE (if button exists)
    var btnTheme = document.getElementById('btn-theme-toggle');
    if (btnTheme) {
        btnTheme.addEventListener('click', function () {
            var body = document.body;
            var isYellow = body.classList.contains('theme-yellow') || (!body.classList.contains('theme-yellow') && !body.classList.contains('theme-blue'));
            if (isYellow) {
                body.classList.remove('theme-yellow');
                body.classList.add('theme-blue');
                localStorage.setItem('gomeal-theme', 'theme-blue');
            } else {
                body.classList.remove('theme-blue');
                body.classList.add('theme-yellow');
                localStorage.setItem('gomeal-theme', 'theme-yellow');
            }
        });
    }

    // FAVORITE (heart) toggle
    document.querySelectorAll('.heart-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            btn.classList.toggle('active');
            // toggle heart icon (outline to solid)
            if (btn.classList.contains('active')) btn.innerHTML = '&#10084;'; // solid heart
            else btn.innerHTML = '&#9825;'; // outline
            // optional: call API to save favorite
            // fetch('/api/favorite', {method:'POST', body:...})
        });
    });

    // ADD TO CART small animation & toast
    document.querySelectorAll('.add-to-cart').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var card = btn.closest('.item-card');
            if (!card) return;
            // simple pulse animation
            card.style.transition = 'transform .12s ease';
            card.style.transform = 'scale(0.98)';
            setTimeout(function () { card.style.transform = ''; }, 120);

            // add to cart visual (could be improved)
            var name = card.querySelector('h4') ? card.querySelector('h4').innerText : 'Item';
            if (window.showToast) showToast(name + ' added to cart');
        });
    });

    // PAGINATION demo
    document.querySelectorAll('.pagination .page').forEach(function (p) {
        p.addEventListener('click', function (e) {
            if (p.classList.contains('prev') || p.classList.contains('next')) {
                // simple demo: rotate active page
                var active = document.querySelector('.pagination .page.active');
                var pages = Array.from(document.querySelectorAll('.pagination .page')).filter(function (x) { return !x.classList.contains('prev') && !x.classList.contains('next'); });
                var idx = pages.indexOf(active);
                if (p.classList.contains('prev')) idx = Math.max(0, idx - 1);
                else idx = Math.min(pages.length - 1, idx + 1);
                pages.forEach(function (x) { x.classList.remove('active'); });
                pages[idx].classList.add('active');
            } else {
                document.querySelectorAll('.pagination .page').forEach(function (x) { x.classList.remove('active'); });
                p.classList.add('active');
            }
        });
    });

});