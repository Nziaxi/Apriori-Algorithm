<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Apriori</title>
</head>

<body>
    <h1>Hasil Algoritma Apriori</h1>

    <!-- Tampilkan Itemset yang Terbentuk -->
    <h2>Data Transaksi</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Tanggal Transaksi</th>
                <th>Itemset</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemSets as $index => $itemSet)
                <tr>
                    <td>Transaksi {{ $index + 1 }}</td>
                    <td>{{ implode(', ', $itemSet) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tampilkan Hasil Algoritma Apriori -->
    <h2>Hasil Algoritma Apriori</h2>
    <h2>1-Itemsets</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Itemset</th>
                <th>Support</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results['1-itemset'] as $itemset)
                <tr>
                    <td>{{ implode(', ', $itemset['itemset']) }}</td>
                    <td>{{ $itemset['support'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>2-Itemsets</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Itemset</th>
                <th>Support</th>
                <th>Confidence</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results['2-itemsets'] as $itemset)
                <tr>
                    <td>{{ implode(', ', $itemset['itemset']) }}</td>
                    <td>{{ $itemset['support'] }}</td>
                    <td>
                        @foreach ($itemset['confidence'] as $conf)
                            {{ $conf }} <br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>3-Itemsets</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Itemset</th>
                <th>Support</th>
                <th>Confidence</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($results['3-itemsets'] as $itemset)
                <tr>
                    <td>{{ implode(', ', $itemset['itemset']) }}</td>
                    <td>{{ $itemset['support'] }}</td>
                    <td>
                        @foreach ($itemset['confidence'] as $conf)
                            {{ $conf }} <br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="/apriori">Kembali</a>
</body>

</html>
