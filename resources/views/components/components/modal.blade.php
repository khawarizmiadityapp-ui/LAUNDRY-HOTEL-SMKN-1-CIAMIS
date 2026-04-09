{{--
    Komponen: Modal Reusable
    Penggunaan:
    <x-modal name="confirm-payment" title="Konfirmasi Pembayaran" maxWidth="md">
        ... konten modal ...
    </x-modal>

    Untuk membuka: $dispatch('open-modal', 'confirm-payment')
    Untuk menutup: $dispatch('close-modal', 'confirm-payment')
    atau klik backdrop / tombol close
--}}

@props([
    'name'     => 'modal',
    'title'    => 'Modal',
    'maxWidth' => 'md', // 'sm' | 'md' | 'lg' | 'xl'
])

@php
    $maxWidthClass = match($maxWidth) {
        'sm'  => 'max-w-sm',
        'lg'  => 'max-w-lg',
        'xl'  => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<div
    x-data="{ open: false }"
    x-on:open-modal.window="$event.detail === '{{ $name }}' && (open = true)"
    x-on:close-modal.window="$event.detail === '{{ $name }}' && (open = false)"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    x-cloak
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
    ></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full {{ $maxWidthClass }} bg-white rounded-2xl shadow-2xl shadow-slate-300/40 overflow-hidden"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-800">{{ $title }}</h3>
            <button
                @click="open = false"
                class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                aria-label="Tutup modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            {{ $slot }}
        </div>
    </div>
</div>
