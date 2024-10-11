<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container mt-5">
        <h2>Tambah Menu</h2>

        <form action="{{ route('menus.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="code">Kode Menu</label>
                <input type="text" name="code" class="form-control" id="code" required>
            </div>
            <div class="form-group">
                <label for="name">Nama Menu</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="form-group">
                <label for="category">Kategori</label>
                <input type="text" name="category" class="form-control" id="category" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>
