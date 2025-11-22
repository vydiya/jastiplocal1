// basic interactivity: sidebar active + theme toggle
document.addEventListener('DOMContentLoaded', function () {
    // sidebar active (demo)
    document.querySelectorAll('.sidebar .nav .nav-item').forEach(item => {
        item.addEventListener('click', function (e) {
            document.querySelectorAll('.sidebar .nav .nav-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // theme toggle button
    const btn = document.getElementById('btn-theme-toggle');
    function setTheme(name) {
        document.body.classList.remove('theme-yellow', 'theme-blue');
        if (name) document.body.classList.add(name);
        localStorage.setItem('gomeal-theme', name);
    }
    // load stored theme
    const stored = localStorage.getItem('gomeal-theme');
    if (stored) setTheme(stored);

    if (btn) {
        btn.addEventListener('click', function () {
            const isYellow = document.body.classList.contains('theme-yellow') || (!document.body.classList.contains('theme-blue') && !document.body.classList.contains('theme-yellow'));
            setTheme(isYellow ? 'theme-blue' : 'theme-yellow');
        });
    }
});