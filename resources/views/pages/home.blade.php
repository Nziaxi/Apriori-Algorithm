@extends('layout.master')

@section('title')
    Beranda
@endsection

@section('content')
    <section class="section">
        <div class="row">

            <div class="col-xxl-4 col-md-6">
                <div class="card info-card sales-card">

                    <div class="card-body">
                        <h5 class="card-title">Jumlah Menu</span></h5>

                        <div class="d-flex align-items-center">
                            <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="ps-3">
                                <h6>{{ $menuCount }}</h6>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Menu</h5>
                        <p class="card-text">
                            <strong>{{ $menuCount }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Transaksi</h5>
                        <p class="card-text">
                            <strong>{{ $transactionCount }}</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Pengguna</h5>
                        <p class="card-text">
                            <strong>{{ $userCount }}</strong>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
