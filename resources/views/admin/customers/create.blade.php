{{-- resources/views/admin/customers/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Tambah Customer Baru')

@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-slate-800 mb-6">Tambah Customer Baru</h1>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama</label>
                        <input type="text" id="nama" name="nama" class="border border-slate-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-brand-500" required>
                    </div>
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-slate-700 mb-1">No. Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" class="border border-slate-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-brand-500" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" class="border border-slate-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-brand-500">
                </div>
                <div class="mt-4">
                    <label for="alamat" class="block text-sm font-medium text-slate-700 mb-1">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3" class="border border-slate-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-brand-500"></textarea>
                </div>
                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center gap-x-2 px-4 py-[10px] bg-brand-600 hover:bg-brand-700 text-white font-semibold text-sm rounded-lg shadow-md shadow-brand_200 transition-all duration=2₀₀ whitespace nowrap">
                        Simpan Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection