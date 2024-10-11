<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="container mt-5">
        <h2>Tambah Transaksi</h2>

        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="transaction_date">Tanggal Transaksi</label>
                <input type="date" name="transaction_date" class="form-control" id="transaction_date" required>
            </div>
            <div class="form-group">
                <label for="menu_code">Kode Menu</label>
                <input type="text" name="menu_code" class="form-control" id="menu_code" required>
            </div>
            <div class="form-group">
                <label for="quantity">Kuantitas</label>
                <input type="number" name="quantity" class="form-control" id="quantity" required>
            </div>
            <div class="form-group">
                <label for="total_price">Total Harga</label>
                <input type="number" name="total_price" class="form-control" id="total_price" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>

</html>