<!-- 4. TRACKING SECTION -->
<section id="tracking" class="py-24 bg-white relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[600px] h-[600px] bg-primary-50 rounded-full blur-[120px] opacity-70 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[600px] h-[600px] bg-accent/5 rounded-full blur-[100px] opacity-70 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-6 tracking-tight">Lacak Cucian Anda</h2>
        <p class="text-lg text-gray-500 mb-12 max-w-2xl mx-auto">
            Ketahui progres cucian Anda secara *real-time*. Cukup masukkan nomor resi atau ID Invoice yang Anda dapatkan saat melakukan pemesanan.
        </p>
        
        <!-- Form Pencarian -->
        <form action="{{ route('tracking.check') }}" method="POST" class="max-w-2xl mx-auto bg-white/60 backdrop-blur-xl p-3 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex flex-col sm:flex-row gap-3 hover:shadow-[0_8px_40px_rgb(59,130,246,0.1)] transition-shadow duration-500">
            @csrf
            <div class="relative flex-1 flex items-center">
                <svg class="absolute left-6 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="code" placeholder="Misal: TEFA-882910" required
                    class="w-full pl-16 pr-6 py-4 bg-gray-50/50 hover:bg-white rounded-2xl border-2 border-transparent focus:border-primary-400 focus:bg-white focus:ring-4 focus:ring-primary-50 transition-all outline-none text-gray-900 font-medium placeholder-gray-400 text-lg">
            </div>
            <button type="submit" class="px-10 py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold text-lg rounded-2xl shadow-glow shadow-primary-500/40 transition-all duration-300 transform active:scale-95 flex items-center justify-center gap-2 group">
                Lacak
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>

        <!-- Dummy Result Card (Untuk Demonstrasi) -->
        <div class="mt-16 bg-white border border-gray-100 rounded-[2rem] p-8 md:p-10 shadow-soft text-left max-w-2xl mx-auto relative group hover:shadow-xl transition-all duration-500">
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-primary-400 via-primary-500 to-accent rounded-t-[2rem]"></div>
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 border-b border-gray-100 pb-6 gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <p class="text-xs text-primary-600 uppercase font-bold tracking-widest">NO. INVOICE</p>
                        <span class="px-2.5 py-0.5 bg-blue-50 text-primary-600 text-[10px] font-bold rounded-full border border-blue-100">Ditemukan</span>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900 font-mono">TEFA-882910</p>
                </div>
                <div class="px-5 py-2.5 bg-yellow-50 text-yellow-700 text-sm font-bold rounded-xl border border-yellow-200 flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                    </span>
                    Proses Setrika
                </div>
            </div>
            
            <div class="space-y-6 md:space-y-0 md:flex md:justify-between relative">
                <!-- Line on desktop -->
                <div class="hidden md:block absolute top-[11px] left-6 right-6 h-0.5 bg-gray-100"></div>

                <!-- Status 1 -->
                <div class="flex items-start md:flex-col md:items-center gap-4 relative z-10 w-full">
                    <div class="w-6 h-6 rounded-full bg-green-500 border-4 border-white shadow-sm flex-shrink-0 flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="md:text-center mt-0 md:mt-2">
                        <p class="text-sm font-bold text-gray-900">Diterima</p>
                        <p class="text-[11px] text-gray-400">08:30 WIB</p>
                    </div>
                </div>

                <!-- Status 2 -->
                <div class="flex items-start md:flex-col md:items-center gap-4 relative z-10 w-full">
                    <div class="w-6 h-6 rounded-full bg-green-500 border-4 border-white shadow-sm flex-shrink-0 flex items-center justify-center text-white">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="md:text-center mt-0 md:mt-2">
                        <p class="text-sm font-bold text-gray-900">Dicuci</p>
                        <p class="text-[11px] text-gray-400">10:15 WIB</p>
                    </div>
                </div>

                <!-- Status 3 -->
                <div class="flex items-start md:flex-col md:items-center gap-4 relative z-10 w-full">
                    <div class="w-6 h-6 rounded-full bg-primary-500 border-4 border-white shadow-soft flex-shrink-0 flex items-center justify-center">
                        <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                    </div>
                    <div class="md:text-center mt-0 md:mt-2">
                        <p class="text-sm font-extrabold text-primary-600">Disetrika</p>
                        <p class="text-[11px] text-primary-400 font-medium">Sedang proses...</p>
                    </div>
                </div>

                <!-- Status 4 -->
                <div class="flex items-start md:flex-col md:items-center gap-4 relative z-10 w-full opacity-40 grayscale">
                    <div class="w-6 h-6 rounded-full bg-gray-200 border-4 border-white shadow-sm flex-shrink-0"></div>
                    <div class="md:text-center mt-0 md:mt-2">
                        <p class="text-sm font-medium text-gray-500">Siap Ambil/Antar</p>
                        <p class="text-[11px] text-gray-400">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
