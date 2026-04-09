{{--
    Komponen: Input Field
    Penggunaan:
    <x-input
        name="nama_customer"
        label="Nama Customer"
        type="text"
        placeholder="Masukkan nama..."
        required
        :error="$errors->first('nama_customer')"
    />
--}}

@props([
    'name'        => '',
    'label'       => '',
    'type'        => 'text',
    'placeholder' => '',
    'required'    => false,
    'disabled'    => false,
    'error'       => null,
    'hint'        => null,
    'prefix'      => null,  // icon/text sebelum input
    'suffix'      => null,  // icon/text sesudah input
    'value'       => null,
])

<div class="flex flex-col gap-1.5">
    {{-- Label --}}
    @if($label)
        <label
            for="{{ $name }}"
            class="text-xs font-semibold text-slate-700"
        >
            {{ $label }}
            @if($required)
                <span class="text-red-500 ml-0.5">*</span>
            @endif
        </label>
    @endif

    {{-- Input wrapper --}}
    <div class="relative flex items-center">
        {{-- Prefix --}}
        @if($prefix)
            <div class="absolute left-3 flex items-center text-slate-400 pointer-events-none">
                {!! $prefix !!}
            </div>
        @endif

        {{-- Input field --}}
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            value="{{ $value ?? old($name) }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => '
                    w-full text-sm text-slate-700 bg-white
                    border rounded-xl px-3.5 py-2.5
                    transition-all duration-150 outline-none
                    placeholder-slate-400
                    focus:ring-2 focus:ring-sky-500/20 focus:border-sky-400
                    disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed
                    ' . ($error ? 'border-red-300 focus:ring-red-500/20 focus:border-red-400' : 'border-slate-200 hover:border-slate-300')
                    . ($prefix ? ' pl-9' : '')
                    . ($suffix ? ' pr-9' : '')
            ]) }}
        >

        {{-- Suffix --}}
        @if($suffix)
            <div class="absolute right-3 flex items-center text-slate-400 pointer-events-none">
                {!! $suffix !!}
            </div>
        @endif
    </div>

    {{-- Hint --}}
    @if($hint && !$error)
        <p class="text-[11px] text-slate-400">{{ $hint }}</p>
    @endif

    {{-- Error message --}}
    @if($error)
        <p class="text-[11px] text-red-500 flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
