{{-- resources/views/petugas_piket/sidebar.blade.php --}}
{{-- FIXED: Dynamic Sidebar v3.0 - No Helper Dependency --}}

@php
    use App\Services\MenuService;
    use Illuminate\Support\Facades\Log;
    
    // Initialize variables with defaults
    $brand = ['name' => 'BeningLaundry', 'tagline' => 'Management Portal'];
    $divisionLabel = 'Staff';
    $initials = 'SP';
    $menus = [];
    $debugInfo = [];
    
    try {
        // Get MenuService instance
        $menuService = app(MenuService::class);
        $user = auth()->user();
        
        if ($user) {
            // Get brand info
            $brand = $menuService->getBrandInfo();
            
            // Get division label
            $divisionLabel = $menuService->getDivisionLabel($user->division ?? null);
            
            // Get user initials
            $initials = $menuService->getUserInitials($user->name ?? null);
            
            // ✅ FIX: Detect menu type based on current route
            // If we're on admin routes, load admin menus
            // If we're on petugas routes, load petugas menus
            $menuType = 'petugas'; // default
            if (request()->is('admin*')) {
                $menuType = 'admin';
            }
            
            // Get menus for user
            $menus = $menuService->getMenusForUser($menuType);
            
            // Debug info
            $debugInfo = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'user_division' => $user->division,
                'menu_type' => $menuType,
                'menu_count' => count($menus),
                'current_route' => request()->path(),
                'service_loaded' => true,
            ];
        } else {
            $debugInfo['error'] = 'User not authenticated';
        }
        
    } catch (\Exception $e) {
        // Log error for debugging
        Log::error('Sidebar Error: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $debugInfo = [
            'error' => $e->getMessage(),
            'service_loaded' => false,
        ];
    }
@endphp

<aside id="sidebar"
       class="fixed top-0 left-0 h-full w-64 bg-white border-r border-slate-100 z-30 flex flex-col py-6 px-4 gap-6 -translate-x-full md:translate-x-0">  
    {{-- Brand --}}
    <div class="px-2">
        <span class="text-2xl font-extrabold tracking-tight text-slate-900">{{ $brand['name'] }}</span>
    </div>

    {{-- User Info --}}
    @if(auth()->check())
    <div class="flex items-center gap-3 px-2 py-3 bg-slate-50 rounded-xl">
        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-sm shrink-0">
            {{ $initials }}
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name ?? 'Staff Portal' }}</p>
            <div class="flex items-center gap-1.5 mt-0.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 live-dot"></span>
                <span class="text-xs text-emerald-600 font-medium">{{ $divisionLabel }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Navigation Menu --}}
    <nav class="flex-1 px-2 space-y-1 overflow-y-auto">
        @forelse ($menus as $menu)
            <a href="{{ $menu['url'] ?? '#' }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ ($menu['is_active'] ?? false) ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                
                @if(isset($menu['icon']))
                <svg class="w-[18px] h-[18px] shrink-0 {{ ($menu['is_active'] ?? false) ? 'text-white' : 'text-slate-400' }}" 
                     fill="none" 
                     viewBox="0 0 24 24" 
                     stroke-width="1.8" 
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $menu['icon'] }}" />
                </svg>
                @endif
                
                <span class="truncate">{{ $menu['label'] ?? 'Menu' }}</span>
                
                {{-- Badge support (optional) --}}
                @if(isset($menu['badge']) && !empty($menu['badge']))
                    <span class="ml-auto px-2 py-0.5 text-xs font-semibold rounded-full {{ ($menu['is_active'] ?? false) ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600' }}">
                        {{ ${$menu['badge']} ?? 0 }}
                    </span>
                @endif
            </a>
        @empty
            <div class="text-center py-8 text-slate-400 text-sm">
                <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="font-medium">No menu available</p>
                @if(auth()->check())
                <div class="mt-3 text-xs space-y-1">
                    <p><strong>User:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Division:</strong> {{ auth()->user()->division ?? 'Not set' }}</p>
                    <p><strong>Role:</strong> {{ auth()->user()->role ?? 'Not set' }}</p>
                </div>
                @else
                <p class="text-xs mt-2">Please login to see menus</p>
                @endif
            </div>
        @endforelse
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-slate-100">
        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-rose-500 hover:bg-rose-50 transition-all duration-150">
            <svg class="w-[18px] h-[18px] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
            </svg>
            <span>Logout</span>
        </button>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
</aside>
