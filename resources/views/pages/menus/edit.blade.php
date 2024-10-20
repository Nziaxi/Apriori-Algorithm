@extends('layout.master')

@section('title')
    Ubah Data Menu
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('menus.index') }}">Data Menu</a></li>
    <li class="breadcrumb-item active">Ubah</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Ubah Data</h5>

                    <form action="{{ route('menus.update', $menu->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="code" class="col-sm-2 col-form-label">Kode Menu</label>
                            <div class="col-sm-10">
                                <input type="text" name="code" class="form-control" id="code"
                                    placeholder="Masukkan Kode Menu" value="{{ $menu->code }}">
                                @error('code')
                                    <span class="text-danger">Kode menu harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="name" class="col-sm-2 col-form-label">Nama Menu</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Masukkan Nama Menu" value="{{ $menu->name }}">
                                @error('name')
                                    <span class="text-danger">Nama menu harus diisi.</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="category" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-select" name="category" id="category" required>
                                    <option value="" disabled>Pilih Kategori</option>
                                    <option value="Makanan Utama"
                                        {{ $menu->category == 'Makanan Utama' ? 'selected' : '' }}>Makanan Utama</option>
                                    <option value="Minuman" {{ $menu->category == 'Minuman' ? 'selected' : '' }}>
                                        Minuman</option>
                                    <option value="Snack" {{ $menu->category == 'Snack' ? 'selected' : '' }}>Snack
                                    </option>
                                    <option value="Dessert" {{ $menu->category == 'Dessert' ? 'selected' : '' }}>
                                        Dessert</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                Perbarui</button>
                            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
@endsection
