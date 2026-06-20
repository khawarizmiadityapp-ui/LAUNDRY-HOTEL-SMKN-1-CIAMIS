{{-- resources/views/admin/customers/edit.blade.php --}}
@extends('layouts.' . (auth()->user()->role === 'admin' ? 'admin' : 'petugas_piket'))

@section('title', 'Edit Data Customer')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Data Customer</h1>
        <p class="text-sm text-slate-400 font-medium mt-0.5">Perbarui informasi pelanggan ini.</p>
    </div>
    <a href="{{ route('admin.customers.index') }}" class="text-slate-500 hover:text-brand-600 font-medium text-sm transition-colors">
        &larr; Kembali
    </a>
</div>

<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nama" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $customer->nama) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none">
            </div>
            <div>
                <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-1.5">No. Telepon (WA)</label>
                <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $customer->no_hp) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none">
            </div>
        </div>
        <div class="mt-5">
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email (Opsional)</label>
            <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                   class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none">
        </div>
        <div class="mt-5">
            <label for="alamat" class="block text-sm font-semibold text-slate-700 mb-1.5">Alamat Lengkap</label>
            <textarea id="alamat" name="alamat" rows="3"
                      class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all outline-none">{{ old('alamat', $customer->alamat) }}</textarea>
        </div>
        <div class="mt-8 flex items-center justify-end">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-brand-600 hover:bg-brand-700 text-white
                           font-bold text-sm rounded-xl shadow-md shadow-brand-200 transition-all duration-200">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
