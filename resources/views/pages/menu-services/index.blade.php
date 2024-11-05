@extends('layout.master')

@section('title')
    Pilih Menu
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Pilih Menu</li>
@endsection

@section('content')
    <section class="section">
        <form action="/validate-menu" method="POST">
            @csrf
            <div class="row">
                @foreach ($menus as $menu)
                    <div class="col-md-4">
                        <div class="card">
                            {{-- <img src="{{ $menu->image }}" class="card-img-top" alt="{{ $menu->name }}"> --}}
                            <div class="card-body">
                                <h5 class="card-title">{{ $menu->name }}</h5>
                                <p class="card-text">Rp{{ number_format($menu->price, 0, ',', '.') }}</p>

                                <div class="d-flex justify-content-end align-items-center">
                                    <div id="counter-{{ $menu->id }}" class="d-none align-items-center">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            onclick="decrement('{{ $menu->id }}')">-</button>
                                        <span id="quantity-{{ $menu->id }}" class="mx-2">1</span>
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            onclick="increment('{{ $menu->id }}')">+</button>
                                    </div>
                                    <button type="button" class="btn btn-success btn-add ms-auto"
                                        data-menu-id="{{ $menu->id }}">
                                        Tambah
                                    </button>
                                    <input type="hidden" class="menu-quantity" name="selected_menus[{{ $menu->name }}]"
                                        value="0" id="input-quantity-{{ $menu->id }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Floating Button Container -->
            <div id="continue-button" class="fixed-bottom bg-light py-2 text-center d-none">
                <button type="submit" class="btn btn-primary">Lanjutkan</button>
            </div>
        </form>
    </section>

    <script>
        // Show/Hide the continue button based on item selection
        function updateContinueButtonVisibility() {
            const quantities = document.querySelectorAll('.menu-quantity');
            const isAnySelected = Array.from(quantities).some(input => parseInt(input.value) > 0);
            const continueButton = document.getElementById('continue-button');
            continueButton.classList.toggle('d-none', !isAnySelected);
        }

        // Show/Hide the counter based on button 'Tambah' is clicked
        document.querySelectorAll('.btn-add').forEach(button => {
            button.addEventListener('click', function() {
                const menuId = this.getAttribute('data-menu-id');
                document.getElementById(`counter-${menuId}`).classList.remove('d-none');
                this.classList.add('d-none');
                document.getElementById(`input-quantity-${menuId}`).value = 1;
                document.getElementById(`quantity-${menuId}`).textContent = 1;
                updateContinueButtonVisibility();
            });
        });

        // Increment quantity
        function increment(menuId) {
            const quantityElem = document.getElementById(`quantity-${menuId}`);
            let quantity = parseInt(quantityElem.textContent);
            quantity++;
            quantityElem.textContent = quantity;
            document.getElementById(`input-quantity-${menuId}`).value = quantity;
            updateContinueButtonVisibility();
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
            updateContinueButtonVisibility();
        }
    </script>
@endsection
