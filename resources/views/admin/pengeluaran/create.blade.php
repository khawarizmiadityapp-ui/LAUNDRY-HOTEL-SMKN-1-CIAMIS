{{--resources/views/admin/pengeluaran/create.blade.php--}}
@extends('layouts.admin')

@section('title', 'Create Pengeluaran - Bening Laundry')

@section('content')
<h1>Create Pengeluaran</h1>

<p>ID Transaksi: {{ $idTransaksi }}</p>

<form action="{{ route('admin.pengeluaran.store') }}" method="POST">
    @csrf
    <input type="text" name="nama" placeholder="Nama">
    <input type="text" name="kategori" placeholder="Kategori">
    <input type="number" name="nominal" placeholder="Nominal">
    <button type="submit">Simpan</button>
</form>
@endsection