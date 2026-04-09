<nav class="sticky top-0 z-50 w-full bg-white/70 backdrop-blur-xl border-b border-gray-100 shadow-soft transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3 cursor-pointer group">
                <div class="w-8 h-8 flex items-center justify-center text-primary-600 font-black text-xl tracking-tighter">
                    B<span class="text-gray-900">L</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg tracking-tight text-gray-900 leading-none">Bening<span class="text-primary-600"> Laundry</span></span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6 items-center">
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Home</a>
                <a href="#layanan" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Layanan</a>
                <a href="#tracking" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">Tracking</a>
                <div class="h-4 w-px bg-gray-200 mx-2"></div>
                <a href="{{ route('login') }}" class="ml-2 px-5 py-2 text-sm font-semibold border border-transparent hover:border-gray-200 text-gray-900 rounded-md transition-all duration-200">
                    Log in
                </a>
                <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold bg-gray-900 text-white hover:bg-black rounded-md transition-all duration-200">
                    Sign up
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button id="menu-btn" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-primary-600 focus:outline-none transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 absolute w-full shadow-soft transition-all duration-300 origin-top">
        <div class="px-4 pt-4 pb-6 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Home</a>
            <a href="#layanan" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Layanan</a>
            <a href="#tracking" class="block px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Tracking</a>
            <div class="pt-4 mt-2 border-t border-gray-100 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="block px-4 py-2 text-center text-sm font-medium text-gray-900 border border-gray-200 rounded-md">Log in</a>
                <a href="{{ route('login') }}" class="block px-4 py-2 text-center text-sm font-medium text-white bg-gray-900 rounded-md">Sign up</a>
            </div>
        </div>
    </div>
</nav>