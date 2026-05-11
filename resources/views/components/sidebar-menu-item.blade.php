{{-- resources/views/components/sidebar-menu-item.blade.php --}}
@props([
    'label' => '',
    'url' => '#',
    'icon' => '',
    'active' => false,
    'badge' => null,
])

<a href="{{ $url }}"
   {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 ' . ($active ? 'bg-blue-600 text-white shadow-md shadow-blue-200' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900')]) }}>
    
    @if($icon)
        <svg class="w-[18px] h-[18px] shrink-0 {{ $active ? 'text-white' : 'text-slate-400' }}" 
             fill="none" 
             viewBox="0 0 24 24" 
             stroke-width="1.8" 
             stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
        </svg>
    @endif
    
    <span class="truncate">{{ $label }}</span>
    
    @if($badge !== null)
        <span class="ml-auto px-2 py-0.5 text-xs font-semibold rounded-full {{ $active ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-600' }}">
            {{ $badge }}
        </span>
    @endif
</a>
