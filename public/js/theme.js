document.addEventListener('DOMContentLoaded', function () {
    const htmlTag = document.documentElement;
    const modeSelect = document.querySelector('select[name="interface_mode"]');
    const colorRadios = document.querySelectorAll('input[name="primary_color"]');

    if (!modeSelect && colorRadios.length === 0) return;

    // Primary Colors Config Map
    const themeColors = {
        'navy': { bg: '#1e3a8a', hover: '#172554', alertBg: '#eff6ff', alertBorder: '#bfdbfe', alertText: '#1e3a8a' },
        'indigo':  { bg: '#4f46e5', hover: '#4338ca', alertBg: '#eef2ff', alertBorder: '#c7d2fe', alertText: '#3730a3' },
        'blue':    { bg: '#2563eb', hover: '#1d4ed8', alertBg: '#eff6ff', alertBorder: '#bfdbfe', alertText: '#1e40af' },
        'amber':   { bg: '#f59e0b', hover: '#d97706', alertBg: '#fffbeb', alertBorder: '#fde68a', alertText: '#92400e' },
        'purple':  { bg: '#7c3aed', hover: '#6d28d9', alertBg: '#faf5ff', alertBorder: '#e9d5ff', alertText: '#5b21b6' },
        'rose':    { bg: '#e11d48', hover: '#be123c', alertBg: '#fff1f2', alertBorder: '#fecdd3', alertText: '#9f1239' }
    };

    // 1. Live Dark / Light / System Mode Preview
    if (modeSelect) {
        modeSelect.addEventListener('change', function (e) {
            const mode = e.target.value;
            const isDark = mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isDark) {
                htmlTag.classList.add('dark');
            } else {
                htmlTag.classList.remove('dark');
            }
        });
    }

    // 2. Live Primary Color Preview & Alert Box Color
    colorRadios.forEach(radio => {
        radio.addEventListener('change', function (e) {
            const selectedColor = e.target.value;
            const config = themeColors[selectedColor];

            if (!config) return;

            // CSS Variables Update
            document.documentElement.style.setProperty('--primary-bg', config.bg);
            document.documentElement.style.setProperty('--primary-hover', config.hover);
            document.documentElement.style.setProperty('--alert-bg', config.alertBg);
            document.documentElement.style.setProperty('--alert-border', config.alertBorder);
            document.documentElement.style.setProperty('--alert-text', config.alertText);

            // Live Change Buttons
            document.querySelectorAll('button[type="submit"], .bg-navy-600').forEach(btn => {
                btn.style.backgroundColor = config.bg;
            });

            // Live Change Icons & Accent Text
            document.querySelectorAll('svg.text-navy-600, .text-navy-600').forEach(icon => {
                icon.style.color = config.bg;
            });

            // Live Change Success Message Box
            document.querySelectorAll('.theme-success-msg').forEach(msg => {
                msg.style.backgroundColor = config.alertBg;
                msg.style.borderColor = config.alertBorder;
                msg.style.color = config.alertText;
            });
        });
    });
});