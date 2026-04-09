    {{--
    Komponen: Badge Status Order
    Penggunaan: <x-badge :status="$order->status" />
    atau: @include('components.badge', ['status' => $order->status])

    Status yang tersedia:
    - pending        → Abu (belum diproses)
    - sorting        → Oranye (pemilahan)
    - spotting       → Amber (spotting/noda)
    - washing        → Biru (sedang dicuci)
    - ironing        → Ungu (setrika)
    - packing        → Indigo (packing)
    - ready          → Hijau (siap diambil)
    - paid           → Teal (lunas/diserahkan)
    - hold           → Merah (hold/komplain)
--}}

@props(['status' => 'pending', 'size' => 'sm'])

@php
    $statusMap = [
        'pending'  => ['label' => 'Menunggu',     'class' => 'bg-slate-100 text-slate-600 ring-slate-200'],
        'sorting'  => ['label' => 'Pemilahan',    'class' => 'bg-orange-50 text-orange-600 ring-orange-200'],
        'spotting' => ['label' => 'Spotting',     'class' => 'bg-amber-50 text-amber-600 ring-amber-200'],
        'washing'  => ['label' => 'Dicuci',       'class' => 'bg-sky-50 text-sky-600 ring-sky-200'],
        'ironing'  => ['label' => 'Setrika',      'class' => 'bg-violet-50 text-violet-600 ring-violet-200'],
        'packing'  => ['label' => 'Packing',      'class' => 'bg-indigo-50 text-indigo-600 ring-indigo-200'],
        'ready'    => ['label' => 'Siap Diambil', 'class' => 'bg-emerald-50 text-emerald-600 ring-emerald-200'],
        'paid'     => ['label' => 'Lunas',        'class' => 'bg-teal-50 text-teal-600 ring-teal-200'],
        'hold'     => ['label' => 'Hold',         'class' => 'bg-red-50 text-red-600 ring-red-200'],
    ];

    $config = $statusMap[$status] ?? $statusMap['pending'];

    $dotColorMap = [
        'pending'  => 'bg-slate-400',
        'sorting'  => 'bg-orange-500',
        'spotting' => 'bg-amber-500',
        'washing'  => 'bg-sky-500',
        'ironing'  => 'bg-violet-500',
        'packing'  => 'bg-indigo-500',
        'ready'    => 'bg-emerald-500',
        'paid'     => 'bg-teal-500',
        'hold'     => 'bg-red-500',
    ];
    $dotColor = $dotColorMap[$status] ?? 'bg-slate-400';

    $sizeClass = $size === 'xs' ? 'text-[10px] px-2 py-0.5' : 'text-xs px-2.5 py-1';
@endphp

<span class="inline-flex items-center gap-1.5 font-medium rounded-full ring-1 {{ $config['class'] }} {{ $sizeClass }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}
        {{ in_array($status, ['washing', 'ironing']) ? 'animate-pulse' : '' }}
    "></span>
    {{ $config['label'] }}
</span>
