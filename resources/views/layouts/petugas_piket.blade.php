<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard petugas_piket') - beninglaundry</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#eef4ff',
                            100: '#dae6ff',
                            200: '#bdd2ff',
                            300: '#90b5fd',
                            400: '#5d8ff9',
                            500: '#3568f4',
                            600: '#1f48e9',
                            700: '#1736d6',
                            800: '#192cad',
                            900: '#1a2b88',
                            950: '#141d54',
                        },
                    }
                }
            }
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Sidebar transition */
        #sidebar { transition: transform 0.3s ease; }

        /* Circle progress */
        .ring-blue {
            background: conic-gradient(#2563eb 0% 75%, #e2e8f0 75% 100%);
        }
        .ring-green {
            background: conic-gradient(#16a34a 0% 95%, #e2e8f0 95% 100%);
        }

        /* Animated LIVE badge */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .live-dot { animation: pulse-dot 1.4s ease-in-out infinite; }

        /* Smooth card hover */
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }

        /* Sidebar overlay */
        #sidebar-overlay { transition: opacity 0.3s ease; }

        /* Fade in page */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .delay-1 { animation-delay: 0.05s; opacity: 0; }
        .delay-2 { animation-delay: 0.10s; opacity: 0; }
        .delay-3 { animation-delay: 0.15s; opacity: 0; }
        .delay-4 { animation-delay: 0.20s; opacity: 0; }
        .delay-5 { animation-delay: 0.25s; opacity: 0; }

        /* Blur decoration */
        .blur-shape {
            filter: blur(60px);
            pointer-events: none;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            min-width: 300px;
            padding: 16px 20px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease;
            border-left: 4px solid;
        }
        .toast.success { border-left-color: #10b981; }
        .toast.error { border-left-color: #ef4444; }
        .toast.warning { border-left-color: #f59e0b; }
        .toast.info { border-left-color: #3b82f6; }
        .toast-icon {
            width: 24px;
            height: 24px;
            flex-shrink: 0;
        }
        .toast.success .toast-icon { color: #10b981; }
        .toast.error .toast-icon { color: #ef4444; }
        .toast.warning .toast-icon { color: #f59e0b; }
        .toast.info .toast-icon { color: #3b82f6; }
        .toast-content {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
        }
        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            padding: 4px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .toast-close:hover {
            background: #f1f5f9;
            color: #64748b;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        .toast.removing {
            animation: slideOut 0.3s ease forwards;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

<!-- Toast Container -->
<div id="toast-container" class="toast-container"></div>

{{-- ======================================================
     MOBILE OVERLAY
     ====================================================== --}}
<div id="sidebar-overlay"
     class="fixed inset-0 bg-black/30 z-20 hidden md:hidden"
     onclick="toggleSidebar()"></div>

{{-- ======================================================
     LAYOUT WRAPPER
     ====================================================== --}}
<div class="flex min-h-screen">

    {{-- ====================================================
         SIDEBAR
         ==================================================== --}}
    @include('petugas_piket.sidebar')

    {{-- ====================================================
         MAIN CONTENT
         ==================================================== --}}
    <main class="flex-1 md:ml-64 min-h-screen">

        {{-- Mobile top bar --}}
        <div class="md:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-slate-100">
            <span class="text-xl font-extrabold tracking-tight text-slate-900">Orchestra</span>
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-slate-100 text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        @yield('sticky_topbar')

        <div class="p-4 md:p-8 space-y-6 max-w-screen-xl mx-auto">
            @if(session('notification_link'))
            <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-200 animate-bounce transition-all">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </div>
                        <div>
                            <p class="font-bold text-lg leading-tight uppercase tracking-tight">Kirim Notifikasi Progress!</p>
                            <p class="text-indigo-100 text-sm">Tahap pekerjaan telah selesai diperbarui. Beri tahu pelanggan lewat WhatsApp sekarang.</p>
                        </div>
                    </div>
                    <a href="{{ session('notification_link') }}" target="_blank" class="px-6 py-2.5 bg-white text-indigo-600 rounded-xl font-bold hover:bg-slate-50 transition-colors shadow-lg whitespace-nowrap">
                        Notifikasi via WA
                    </a>
                </div>
            </div>
            @endif

            <div class="p-6">
                {{-- INI YANG DIGANTI-GANTI --}}
                @yield('content')
            </div>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');
        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }

    // Otomatis buka link WA jika ada
    @if(session('notification_link'))
        window.onload = function() {
            setTimeout(function() {
                window.location.href = "{!! session('notification_link') !!}";
            }, 1000);
        };
    @endif

    // Sinkronisasi dan persistence nama petugas piket menggunakan localStorage
    document.addEventListener('DOMContentLoaded', function () {
        const petugasInputs = document.querySelectorAll('[name="petugas_name"]');
        if (petugasInputs.length === 0) return;

        // Ambil nama tersimpan dari localStorage
        const savedName = localStorage.getItem('petugas_piket_name');

        // Pre-fill semua input jika ada nama tersimpan
        if (savedName) {
            petugasInputs.forEach(input => {
                input.value = savedName;
            });
        }

        // Daftarkan listener untuk mendeteksi perubahan input dan sinkronisasikan
        petugasInputs.forEach(input => {
            const syncEvent = function (e) {
                const newName = e.target.value;
                
                // Simpan ke localStorage
                localStorage.setItem('petugas_piket_name', newName);

                // Sinkronkan ke seluruh input dengan name="petugas_name" di halaman yang sama
                petugasInputs.forEach(otherInput => {
                    if (otherInput !== e.target) {
                        otherInput.value = newName;
                    }
                });
            };

            input.addEventListener('input', syncEvent);
            input.addEventListener('change', syncEvent);
        });
    });

    // Global loading state for forms
    document.addEventListener('submit', function (e) {
        if(e.target.tagName === 'FORM') {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            if(submitBtn && !submitBtn.disabled) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

                // Safety timeout to re-enable after 10s if something fails
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 10000);
            }
        }
    });

    // Toast notification system
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;

        const icons = {
            success: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            error: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>',
            warning: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>',
            info: '<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>'
        };

        toast.innerHTML = `
            ${icons[type] || icons.info}
            <div class="toast-content">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        `;

        container.appendChild(toast);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    // Show Laravel flash messages as toasts
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif
    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    @if(session('warning'))
        showToast('{{ session('warning') }}', 'warning');
    @endif
    @if(session('info'))
        showToast('{{ session('info') }}', 'info');
    @endif

    // Alpine component for searchable petugas input
    window.petugasSearchComponent = function(petugasList) {
        return {
            list: petugasList,
            filteredList: petugasList,
            search: '',
            showDropdown: false,
            isInvalid: false,
            
            init() {
                this.filteredList = this.list;
                
                // Load initial name from localStorage if available
                const savedName = localStorage.getItem('petugas_piket_name');
                if (savedName) {
                    this.search = savedName;
                    this.filterPetugas();
                }
                
                // Watch search changes to sync with localStorage
                this.$watch('search', value => {
                    localStorage.setItem('petugas_piket_name', value);
                });
            },
            
            filterPetugas() {
                // limit input to 50 words
                let words = this.search.split(/\s+/);
                if (words.length > 50) {
                    this.search = words.slice(0, 50).join(' ');
                }
                
                const q = this.search.toLowerCase().trim();
                if (q === '') {
                    this.filteredList = this.list;
                    this.isInvalid = false;
                } else {
                    this.filteredList = this.list.filter(p => p.nama.toLowerCase().includes(q));
                    // check exact match
                    this.isInvalid = !this.list.some(p => p.nama.toLowerCase() === q);
                }
                
                // HTML5 validation
                let inputEl = this.$el.querySelector('input[name="petugas_name"]');
                if (inputEl) {
                    if (this.isInvalid) {
                        inputEl.setCustomValidity('Petugas tidak terdaftar');
                    } else {
                        inputEl.setCustomValidity('');
                    }
                }
            },
            
            select(p) {
                this.search = p.nama;
                this.showDropdown = false;
                this.isInvalid = false;
                
                let inputEl = this.$el.querySelector('input[name="petugas_name"]');
                if (inputEl) {
                    inputEl.setCustomValidity('');
                    inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                    inputEl.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        };
    }
</script>

@stack('scripts')

</body>
</html>