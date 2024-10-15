<div class="container">
    <h1>Halaman Konfirmasi</h1>
    <h2>Pesanan Anda:</h2>

    <ul>
        @foreach (session('order') as $menu)
            <li>{{ $menu }}</li>
        @endforeach
    </ul>
</div>
