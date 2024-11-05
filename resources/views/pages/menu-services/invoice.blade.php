@extends('layout.master')

@section('title')
    Invoice Pesanan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('menu-services.index') }}">Pilih Menu</a></li>
    <li class="breadcrumb-item active">Invoice</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Invoice Pesanan Anda</h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga Satuan</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderDetails as $detail)
                                    <tr>
                                        <td>{{ $detail['name'] }}</td>
                                        <td>Rp{{ number_format($detail['price'], 0, ',', '.') }}</td>
                                        <td>{{ $detail['quantity'] }}</td>
                                        <td>Rp{{ number_format($detail['total'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total Bayar</th>
                                    <th>Rp{{ number_format($totalAmount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>

                        <div class="text-end mt-4">
                            <button onclick="window.print()" class="btn btn-primary">Cetak Invoice</button>
                            <a href="{{ route('menu-services.index') }}" class="btn btn-secondary">Kembali ke Menu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
