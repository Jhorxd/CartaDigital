            Alpine.data('themeManager', () => {
                const tenantTheme = @json($tenant->theme ?? 'auto');

                const syncThemeColor = (dark) => {
                    const meta = document.querySelector('meta[name="theme-color"]');
                    if (meta) {
                        meta.content = dark ? '#0a0a0a' : '#f9fafb';
                    }
                };

                return {
                    darkMode: document.documentElement.classList.contains('dark'),
                    themeSetting: tenantTheme,
                    init() {
                        this.$watch('darkMode', (val) => syncThemeColor(val));
                    },
                    toggleTheme() {
                        if (tenantTheme === 'light' || tenantTheme === 'dark') {
                            return;
                        }
                        this.darkMode = !this.darkMode;
                        try {
                            localStorage.setItem('darkMode', this.darkMode);
                        } catch (e) {}
                    },
                };
            });
