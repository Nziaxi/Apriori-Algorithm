@extends('layout.master')

@section('title')
    Tambah Data Transaksi
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Data Transaksi</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Tambah Data</h5>

                    <form action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="transaction_date" class="col-sm-2 col-form-label">Tanggal Transaksi</label>
                            <div class="col-sm-10">
                                <input type="date" name="transaction_date" class="form-control" id="transaction_date"
                                    placeholder="Masukkan Tanggal Transaksi" value="{{ old('code') }}">
                                @error('transaction_date')
                                    <span class="text-danger">Tanggal transaksi harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="menu_code" class="col-sm-2 col-form-label">Kode Menu</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="menu_code" id="menu_code" required>
                                    <option value="" selected disabled>Pilih Kode Menu</option>
                                    @foreach ($menus as $menu)
                                        <option value="{{ $menu->code }}"
                                            {{ old('menu_code') == $menu->code ? 'selected' : '' }}>
                                            {{ $menu->code }} - {{ $menu->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="quantity" class="col-sm-2 col-form-label">Kuantitas</label>
                            <div class="col-sm-10">
                                <input type="number" name="quantity" class="form-control" id="quantity"
                                    placeholder="Masukkan Kuantitas" value="{{ old('quantity') }}">
                                @error('quantity')
                                    <span class="text-danger">Kuantitas harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="total_price" class="col-sm-2 col-form-label">Total Harga</label>
                            <div class="col-sm-10">
                                <input type="number" name="total_price" class="form-control" id="total_price"
                                    placeholder="Masukkan Total Harga" value="{{ old('total_price') }}">
                                @error('total_price')
                                    <span class="text-danger">Total harga harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                Simpan</button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
