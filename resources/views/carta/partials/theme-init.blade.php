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
</style>
<script>
    requestAnimationFrame(function () {
        document.documentElement.classList.add('theme-ready');
    });
</script>
