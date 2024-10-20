@extends('layout.master')

@section('title')
    Halaman Konfirmasi
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('menu-services.index') }}">Pilih Menu</a></li>
    <li class="breadcrumb-item active">Halaman Konfirmasi</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">Pesanan Anda:</h5>
                    <ul>
                        @foreach (session('order') as $menu)
                            <li>{{ $menu }}</li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </section>
@endsection
