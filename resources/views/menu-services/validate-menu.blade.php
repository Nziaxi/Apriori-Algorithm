<div class="container">
    <h1>Validasi Menu</h1>

    <h2>Menu yang Dipilih:</h2>
    <ul>
        @foreach ($selectedMenus as $menu)
            <li>{{ $menu }}</li>
        @endforeach
    </ul>

    <h2>Rekomendasi Menu Berdasarkan Pilihan Anda:</h2>
    <form action="{{ route('confirm.order') }}" method="POST">
        @csrf
        <ul>
            @if (count($recommendations) > 0)
                @foreach ($recommendations as $recommended)
                    <li>
                        <label>
                            <input type="checkbox" name="recommended_menus[]" value="{{ $recommended }}" />
                            {{ $recommended }}
                        </label>
                    </li>
                @endforeach
            @else
                <label>Tidak ada rekomendasi menu yang tersedia berdasarkan pilihan Anda.</label>
            @endif
        </ul>

        <input type="hidden" name="selected_menus[]" value="{{ implode(',', $selectedMenus) }}" />

        <button type="submit">Konfirmasi Pesanan</button>
    </form>
</div>
