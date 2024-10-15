<form action="/validate-menu" method="POST">
    @csrf
    <h2>Pilih Menu</h2>
    @foreach ($menus as $menu)
        <input type="checkbox" name="selected_menus[]" value="{{ $menu->name }}">
        {{ $menu->name }} <br>
    @endforeach
    <button type="submit">Lanjutkan</button>
</form>
