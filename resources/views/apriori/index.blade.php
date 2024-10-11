<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algoritma Apriori</title>
</head>

<body>
    <h1>Algoritma Apriori</h1>
    <form action="/apriori" method="POST">
        @csrf
        <label for="min_support">Minimal Support:</label>
        <input type="number" name="min_support" step="0.01" required>

        <label for="min_confidence">Minimal Confidence:</label>
        <input type="number" name="min_confidence" step="0.01" required>

        <button type="submit">Proses</button>
    </form>
</body>

</html>
