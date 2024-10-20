@extends('layout.master')

@section('title')
    Hasil Algoritma Apriori
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('apriori.index') }}">Generate Paket Menu</a></li>
    <li class="breadcrumb-item active">Hasil Algoritma</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Transaksi</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal Transaksi</th>
                                <th scope="col">Itemset</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($itemSets))
                                <tr>
                                    <td colspan="2" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @else
                                @foreach ($itemSets as $index => $itemSet)
                                    <tr>
                                        <td>{{ $itemSet['date'] }}</td>
                                        <td>{{ implode(', ', array_column($itemSet['items'], 'name')) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">1-Itemsets</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Itemset</th>
                                <th scope="col">Support</th>
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
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">2-Itemsets</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Itemset</th>
                                <th scope="col">Support</th>
                                <th scope="col">Confidence</th>
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
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">3-Itemsets</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Itemset</th>
                                <th scope="col">Support</th>
                                <th scope="col">Confidence</th>
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
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">4-Itemsets</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Itemset</th>
                                <th scope="col">Support</th>
                                <th scope="col">Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results['4-itemsets'] as $itemset)
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

                    <div class="text-center">
                        <a href="{{ route('apriori.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
