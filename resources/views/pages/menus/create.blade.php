@extends('layout.master')

@section('title')
    Tambah Data Menu
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Data Menu</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Tambah Data</h5>

                    <form action="{{ route('menus.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="code" class="col-sm-2 col-form-label">Kode Menu</label>
                            <div class="col-sm-10">
                                <input type="text" name="code" class="form-control" id="code"
                                    placeholder="Masukkan Kode Menu" value="{{ old('code') }}">
                                @error('code')
                                    <span class="text-danger">Kode menu harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Nama Menu</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Masukkan Nama Menu" value="{{ old('name') }}">
                                @error('name')
                                    <span class="text-danger">Nama menu harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="category" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="category" id="category" required>
                                    <option value="" selected disabled>Pilih Kategori</option>
                                    <option value="Makanan Utama">Makanan Utama</option>
                                    <option value="Minuman">Minuman</option>
                                    <option value="Snack">Snack</option>
                                    <option value="Dessert">Dessert</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="price" class="col-sm-2 col-form-label">Harga</label>
                            <div class="col-sm-10">
                                <input type="number" name="price" class="form-control" id="price"
                                    placeholder="Masukkan Harga" value="{{ old('price') }}">
                                @error('price')
                                    <span class="text-danger">Harga harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                Simpan</button>
                            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
