@extends('layout.master')

@section('title')
    Validasi Menu
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('menu-services.index') }}">Pilih Menu</a></li>
    <li class="breadcrumb-item active">Validasi Menu</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-md-6 d-flex justify-content-end">
                <div class="card w-75">
                    <div class="card-body">

                        <h5 class="card-title">Menu yang Dipilih:</h5>
                        <ul>
                            @foreach ($selectedMenus as $menu)
                                <li>{{ $menu }}</li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>

            <div class="col-md-6 d-flex justify-content-start">
                <div class="card w-75">
                    <div class="card-body">

                        <h5 class="card-title">Rekomendasi Menu Berdasarkan Pilihan Anda:</h5>
                        <form action="{{ route('confirm.order') }}" method="POST">
                            @csrf
                            <ul>
                                @if (count($recommendations) > 0)
                                    @foreach ($recommendations as $recommended)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="recommended_menus[]"
                                                value="{{ $recommended }}">
                                            <label class="form-check-label">
                                                {{ $recommended }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <label>Tidak ada rekomendasi menu yang tersedia berdasarkan pilihan Anda.</label>
                                @endif
                            </ul>

                            <input type="hidden" name="selected_menus[]" value="{{ implode(',', $selectedMenus) }}" />

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Konfirmasi Pesanan</button>
                                <a href="{{ route('menu-services.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
