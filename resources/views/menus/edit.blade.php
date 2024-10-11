<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Menu</h2>

        <form action="{{ route('menus.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="code">Kode Menu</label>
                <input type="text" name="code" class="form-control" id="code" value="{{ $menu->code }}"
                    required>
            </div>
            <div class="form-group">
                <label for="name">Nama Menu</label>
                <input type="text" name="name" class="form-control" id="name" value="{{ $menu->name }}"
                    required>
            </div>
            <div class="form-group">
                <label for="category">Kategori</label>
                <input type="text" name="category" class="form-control" id="category" value="{{ $menu->category }}"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>
