@php
    $tenantTheme = $tenant->theme ?? 'auto';
@endphp
<meta name="theme-color" content="#0a0a0a" id="theme-color-meta">
<script>
(function () {
    var theme = @json($tenantTheme);
    var dark = true;

    if (theme === 'dark' || theme === 'split') {
        dark = true;
    } else if (theme === 'light' || theme === 'split_dark') {
        dark = false;
    } else {
        try {
            var stored = localStorage.getItem('darkMode');
            dark = stored === null ? true : stored === 'true';
        } catch (e) {
            dark = true;
        }
    }

    if (dark) {
        document.documentElement.classList.add('dark');
    }

    var meta = document.getElementById('theme-color-meta');
    if (meta) {
        meta.content = dark ? '#0a0a0a' : '#f9fafb';
    }
})();
</script>
<style>
    html { background-color: #f9fafb; color-scheme: light; }
    html.dark { background-color: #0a0a0a; color-scheme: dark; }
    html.theme-ready body {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    /* iPhone: estos efectos dejan la pestaña en blanco al volver a Safari */
    @supports (-webkit-touch-callout: none) {
        *, *::before, *::after {
            -webkit-backdrop-filter: none !important;
            backdrop-filter: none !important;
            background-attachment: scroll !important;
            mix-blend-mode: normal !important;
        }
        img, [class*="blur-"], .glass, .luxury-card {
            -webkit-filter: none !important;
            filter: none !important;
            -webkit-transform: none !important;
            transform: none !important;
        }
    }
</style>
<script>
    requestAnimationFrame(function () {
        document.documentElement.classList.add('theme-ready');
    });
</script>
<script>
(function () {
    var RELOAD_KEY = 'carta-ios-reload';
    var leftSafari = false;

    function reloadOnce() {
        try {
            var stamp = Number(sessionStorage.getItem(RELOAD_KEY) || 0);
            if (stamp && Date.now() - stamp < 8000) return;
            sessionStorage.setItem(RELOAD_KEY, String(Date.now()));
        } catch (e) {}
        window.location.reload();
    }

    function repaint() {
        var body = document.body;
        if (!body) return;
        body.style.opacity = '0.99';
        void body.offsetHeight;
        body.style.opacity = '';
    }

    function unstick() {
        var body = document.body;
        if (!body) return;
        body.style.overflow = '';
        body.style.position = '';
        body.style.width = '';
        body.style.height = '';
    }

    function revealMenu() {
        document.querySelectorAll('[x-cloak]').forEach(function (el) {
            var cls = String(el.className || '');
            if (cls.indexOf('fixed') !== -1) return;
            el.removeAttribute('x-cloak');
        });
    }

    function pageBlank() {
        var text = (document.body && document.body.innerText || '').replace(/\s+/g, '');
        return text.length < 20;
    }

    function recover() {
        unstick();
        repaint();
        if (!window.Alpine) revealMenu();
        window.setTimeout(function () {
            if (!window.Alpine) revealMenu();
            if (pageBlank()) reloadOnce();
        }, 1500);
    }

    window.cartaOpen = function (url) {
        var ios = /iPhone|iPad|iPod/.test(navigator.userAgent);
        if (!ios) {
            var link = document.createElement('a');
            link.href = url;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            document.body.appendChild(link);
            link.click();
            link.remove();
            return;
        }
        var frame = document.createElement('iframe');
        frame.setAttribute('aria-hidden', 'true');
        frame.style.cssText = 'position:absolute;width:0;height:0;border:0;visibility:hidden';
        frame.src = url;
        document.body.appendChild(frame);
        window.setTimeout(function () {
            frame.remove();
            if (document.visibilityState === 'visible') window.location.assign(url);
        }, 700);
    };

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            leftSafari = true;
            var active = document.activeElement;
            if (active && active !== document.body && active.blur) {
                try { active.blur(); } catch (e) {}
            }
            return;
        }
        if (!leftSafari) return;
        recover();
    });

    window.addEventListener('pageshow', function (event) {
        if (!event.persisted && !leftSafari) return;
        recover();
    });
})();
</script>
