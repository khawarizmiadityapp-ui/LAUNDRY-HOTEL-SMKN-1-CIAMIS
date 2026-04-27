{{--
  REUSABLE COMPONENTS UNTUK SIDEBAR & CRUD INTERFACE

  Letakkan file-file ini di: resources/views/components/

  Penggunaan:
  <x-sidebar />
  <x-crud-button type="create" />
  <x-status-badge status="pending" />
--}}

{{-- ============================================
  Component: Sidebar Navigation Item
  File: resources/views/components/nav-item.blade.php
  ============================================ --}}
<a href="{{ $route }}"
   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
          {{ $active
             ? 'bg-blue-600 text-white shadow-md shadow-blue-200'
             : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
    <svg class="w-[18px] h-[18px] shrink-0"
         fill="none"
         viewBox="0 0 24 24"
         stroke-width="1.8"
         stroke="currentColor">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              d="{{ $icon }}" />
    </svg>
    <span class="truncate">{{ $label }}</span>
</a>

{{-- ============================================
  Component: Status Badge
  File: resources/views/components/status-badge.blade.php
  ============================================ --}}
@php
    $statusColors = [
        'pending' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dot' => 'bg-blue-600', 'label' => 'Pending'],
        'in_progress' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dot' => 'bg-yellow-600', 'label' => 'In Progress'],
        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dot' => 'bg-green-600', 'label' => 'Completed'],
        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dot' => 'bg-red-600', 'label' => 'Rejected'],
        'active' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'dot' => 'bg-emerald-600', 'label' => 'Active'],
        'inactive' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-800', 'dot' => 'bg-slate-600', 'label' => 'Inactive'],
    ];

    $config = $statusColors[$status] ?? $statusColors['pending'];
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $config['bg'] }} {{ $config['text'] }} font-medium text-xs">
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ $label ?? $config['label'] }}
</span>

{{-- ============================================
  Component: CRUD Action Buttons
  File: resources/views/components/crud-button.blade.php
  ============================================ --}}
@php
    $styles = [
        'create' => ['bg' => 'bg-blue-600', 'hover' => 'hover:bg-blue-700', 'text' => 'text-white', 'icon' => 'M12 4v16m8-8H4'],
        'edit' => ['bg' => 'bg-amber-600', 'hover' => 'hover:bg-amber-700', 'text' => 'text-white', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
        'delete' => ['bg' => 'bg-red-600', 'hover' => 'hover:bg-red-700', 'text' => 'text-white', 'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'],
        'view' => ['bg' => 'bg-slate-600', 'hover' => 'hover:bg-slate-700', 'text' => 'text-white', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
    ];

    $config = $styles[$type] ?? $styles['create'];
    $label = $label ?? ucfirst($type);
@endphp

<a href="{{ $route }}"
   class="inline-flex items-center gap-2 px-4 py-2 {{ $config['bg'] }} {{ $config['hover'] }} {{ $config['text'] }} rounded-lg font-medium transition-colors duration-200 {{ $class ?? '' }}">
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
    </svg>
    {{ $label }}
</a>

{{-- ============================================
  Component: Form Modal
  File: resources/views/components/modal-form.blade.php
  ============================================ --}}
<div id="{{ $modalId }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 shadow-xl">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
            <button type="button"
                    onclick="document.getElementById('{{ $modalId }}').classList.add('hidden')"
                    class="text-slate-500 hover:text-slate-700">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <form method="{{ $method ?? 'POST' }}" action="{{ $action }}" class="p-6 space-y-4">
            @csrf
            @if($method === 'PATCH' || $method === 'PUT')
                @method($method)
            @endif

            {{ $slot }}

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4">
                <button type="button"
                        onclick="document.getElementById('{{ $modalId }}').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ============================================
  Component: Confirmation Modal
  File: resources/views/components/modal-confirm.blade.php
  ============================================ --}}
<div id="{{ $modalId }}" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl max-w-md w-full mx-4 shadow-xl">
        <div class="p-6 text-center">
            {{-- Icon --}}
            <div class="w-12 h-12 rounded-full {{ $iconBg ?? 'bg-red-100' }} flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 {{ $iconColor ?? 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>

            {{-- Title & Message --}}
            <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $title }}</h3>
            <p class="text-slate-600 text-sm mb-6">{{ $message }}</p>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="button"
                        onclick="document.getElementById('{{ $modalId }}').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50">
                    Cancel
                </button>
                <form method="POST" action="{{ $action }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 {{ $buttonBg ?? 'bg-red-600' }} text-white rounded-lg font-medium {{ $buttonHover ?? 'hover:bg-red-700' }}">
                        {{ $buttonText ?? 'Delete' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ============================================
  Component: Empty State
  File: resources/views/components/empty-state.blade.php
  ============================================ --}}
<div class="text-center py-12">
    <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
    </svg>
    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $title ?? 'No Data Found' }}</h3>
    <p class="text-slate-600 text-sm mb-4">{{ $message ?? 'There is no data to display yet.' }}</p>
    @if(isset($action) && isset($actionUrl))
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ $action }}
        </a>
    @endif
</div>

{{-- ============================================
  Component: Loading Spinner
  File: resources/views/components/loading.blade.php
  ============================================ --}}
<div class="flex items-center justify-center py-12">
    <div class="relative w-12 h-12">
        <div class="absolute inset-0 rounded-full border-4 border-slate-200"></div>
        <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-blue-600 animate-spin"></div>
    </div>
</div>

{{-- ============================================
  Component: Alert
  File: resources/views/components/alert.blade.php
  ============================================ --}}
@php
    $alertStyles = [
        'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-200', 'icon' => 'text-green-600', 'text' => 'text-green-800'],
        'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'icon' => 'text-red-600', 'text' => 'text-red-800'],
        'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'icon' => 'text-yellow-600', 'text' => 'text-yellow-800'],
        'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'icon' => 'text-blue-600', 'text' => 'text-blue-800'],
    ];

    $style = $alertStyles[$type] ?? $alertStyles['info'];
@endphp
<div class="p-4 rounded-lg {{ $style['bg'] }} border {{ $style['border'] }} flex gap-3">
    <svg class="w-5 h-5 mt-0.5 shrink-0 {{ $style['icon'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div>
        <h4 class="font-semibold {{ $style['text'] }}">{{ $title ?? 'Notice' }}</h4>
        <p class="text-sm {{ $style['text'] }} opacity-90 mt-0.5">{{ $message ?? $slot }}</p>
    </div>
</div>
