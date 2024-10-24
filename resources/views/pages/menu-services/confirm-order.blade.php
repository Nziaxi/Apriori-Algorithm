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
                        @foreach (session('order') as $menu)
                            <li>{{ $menu }}</li>
                        @endforeach
                    </ul>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary" id="confirmOrderButton">Pesan</button>
                    </div>
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
        document.getElementById('confirmOrderButton').addEventListener('click', function() {
            // Show the order confirmation modal
            var modal = new bootstrap.Modal(document.getElementById('orderConfirmationModal'));
            modal.show();

            // Redirect to the menu selection page after a delay
            setTimeout(function() {
                modal.hide(); // Optionally hide the modal before redirecting
                window.location.href =
                    "{{ route('menu-services.index') }}"; // Redirect to the menu selection page
            }, 2000); // 2000 milliseconds = 2 seconds
        });
    </script>
@endsection
