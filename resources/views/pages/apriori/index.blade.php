@extends('layout.master')

@section('title')
    Algoritma Apriori
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Generate Paket Menu</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Form Input</h5>
                    <form action="/apriori" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="min_support" class="col-sm-2 col-form-label">Minimal Support:</label>
                            <div class="col-sm-10">
                                <input type="number" name="min_support" step="0.01" class="form-control"
                                    id="min_support" placeholder="Masukkan Minimal Support"
                                    value="{{ old('min_support') }}">
                                @error('min_support')
                                    <span class="text-danger">Minimal support harus diisi.</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="min_confidence" class="col-sm-2 col-form-label">Minimal Confidence:</label>
                            <div class="col-sm-10">
                                <input type="number" name="min_confidence" step="0.01" class="form-control"
                                    id="min_confidence" placeholder="Masukkan Minimal Confidence"
                                    value="{{ old('min_confidence') }}">
                                @error('min_confidence')
                                    <span class="text-danger">Minimal confidence harus diisi.</span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Proses</button>
                        </div>
                    </form>
                </div>
            </div>

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

        </div>
    </section>
@endsection
