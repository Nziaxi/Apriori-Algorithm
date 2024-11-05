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
        <div class="row d-flex justify-content-center align-items-center">
            <div class="card w-75">
                <div class="card-body">

                    <h5 class="card-title">Pesanan Anda:</h5>
                    <ul>
                        @if (session('order'))
                            @foreach (session('order') as $menu)
                                <li>{{ $menu['name'] }} - Jumlah: {{ $menu['quantity'] }}</li>
                            @endforeach
                        @else
                            <li>Tidak ada pesanan yang ditemukan.</li>
                        @endif
                    </ul>

                    <form action="{{ route('store.order') }}" method="POST" id="orderForm">
                        @csrf
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="confirmOrderButton">Pesan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal for Order Confirmation -->
    <div class="modal fade" id="orderConfirmationModal" tabindex="-1" aria-labelledby="orderConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderConfirmationModalLabel">Pesanan Berhasil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('assets/img/icons8-success.gif') }}" alt="Success" class="img-fluid"
                        style="max-width: 200%; height: auto;">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('orderForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting immediately

            // Show the order confirmation modal
            var modal = new bootstrap.Modal(document.getElementById('orderConfirmationModal'));
            modal.show();

            // Wait for a moment, then submit the form
            setTimeout(function() {
                document.getElementById('orderForm').submit();
            }, 2000); // 2000 milliseconds = 2 seconds
        });
    </script>
@endsection
