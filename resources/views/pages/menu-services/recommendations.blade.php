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
            <form action="{{ route('confirm.order') }}" method="POST">
                @csrf
                <div class="col d-flex justify-content-center">
                    <div class="card w-50">
                        <div class="card-body">

                            <h5 class="card-title">Menu yang Dipilih</h5>
                            <ul>
                                @if (count($menusWithQuantity) > 0)
                                    @foreach ($menusWithQuantity as $menu => $quantity)
                                        <p>{{ $menu }} - Jumlah: {{ $quantity }}</p>
                                    @endforeach
                                @else
                                    <li>Tidak ada menu yang dipilih.</li>
                                @endif
                            </ul>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Konfirmasi Pesanan</button>
                                <a href="{{ route('menu-services.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>

                        </div>
                    </div>
                </div>

                <h5 class="card-title">Rekomendasi Menu Berdasarkan Pilihan Anda</h5>
                @if (empty($recommendations))
                    <p>Tidak ada rekomendasi untuk item yang dipilih.</p>
                @else
                    <div class="row">
                        @foreach ($recommendations as $recommendation)
                            @php
                                // Ambil harga menu dari model Menu berdasarkan nama menu yang direkomendasikan
                                $menuItem = \App\Models\Menu::where('name', $recommendation['recommendation'])->first();
                            @endphp
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $recommendation['recommendation'] }}</h5>
                                        <p class="card-text">Confidence: {{ round($recommendation['confidence'] * 100) }}%
                                        </p>
                                        <p class="card-text">Rp{{ number_format($menuItem->price, 0, ',', '.') }}</p>

                                        <div class="d-flex justify-content-end align-items-center">
                                            <div id="counter-{{ $recommendation['recommendation'] }}"
                                                class="d-none align-items-center">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    onclick="decrement('{{ $recommendation['recommendation'] }}')">-</button>
                                                <span id="quantity-{{ $recommendation['recommendation'] }}"
                                                    class="mx-2">1</span>
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    onclick="increment('{{ $recommendation['recommendation'] }}')">+</button>
                                            </div>
                                            <button type="button" class="btn btn-success btn-add ms-auto"
                                                data-menu-id="{{ $recommendation['recommendation'] }}">
                                                Tambah
                                            </button>
                                            <input type="hidden" class="menu-quantity"
                                                name="selected_menus[{{ $recommendation['recommendation'] }}]"
                                                value="0" id="input-quantity-{{ $recommendation['recommendation'] }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @foreach ($menusWithQuantity as $selectedMenus => $quantity)
                    <input type="hidden" name="selected_menus[{{ $selectedMenus }}]" value="{{ $quantity }}" />
                @endforeach
            </form>
        </div>
    </section>

    <script>
        // Show/Hide the counter based on button 'Tambah' is clicked
        document.querySelectorAll('.btn-add').forEach(button => {
            button.addEventListener('click', function() {
                const menuId = this.getAttribute('data-menu-id');
                document.getElementById(`counter-${menuId}`).classList.remove('d-none');
                this.classList.add('d-none');
                document.getElementById(`input-quantity-${menuId}`).value = 1;
                document.getElementById(`quantity-${menuId}`).textContent = 1;
            });
        });

        // Increment quantity
        function increment(menuId) {
            const quantityElem = document.getElementById(`quantity-${menuId}`);
            let quantity = parseInt(quantityElem.textContent);
            quantity++;
            quantityElem.textContent = quantity;
            document.getElementById(`input-quantity-${menuId}`).value = quantity;
        }

        // Decrement quantity
        function decrement(menuId) {
            const quantityElem = document.getElementById(`quantity-${menuId}`);
            let quantity = parseInt(quantityElem.textContent);
            if (quantity > 1) {
                quantity--;
                quantityElem.textContent = quantity;
                document.getElementById(`input-quantity-${menuId}`).value = quantity;
            } else {
                document.getElementById(`counter-${menuId}`).classList.add('d-none');
                document.querySelector(`[data-menu-id="${menuId}"]`).classList.remove('d-none');
                document.getElementById(`input-quantity-${menuId}`).value = 0;
            }
        }
    </script>
@endsection
