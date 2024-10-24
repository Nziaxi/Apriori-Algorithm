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

                    <form method="GET" action="{{ route('apriori.index') }}" class="mb-3">
                        <label for="perPage">Tampilkan entri: </label>
                        <select name="per_page" id="perPage" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal Transaksi</th>
                                <th scope="col">Itemset</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($transactionSets))
                                <tr>
                                    <td colspan="2" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @else
                                @foreach ($transactionSets as $itemSet)
                                    <tr>
                                        <td>{{ $itemSet->transaction_date }}</td>
                                        <td>{{ $itemSet->itemset }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between">
                        <p>Menampilkan {{ $transactionSets->firstItem() }} hingga {{ $transactionSets->lastItem() }} dari
                            {{ $transactionSets->total() }} entri
                        </p>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                {{-- Previous Page Link --}}
                                @if ($transactionSets->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $transactionSets->appends(['per_page' => request('per_page')])->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Pagination Links --}}
                                @foreach ($transactionSets->links()->elements as $element)
                                    {{-- Array Of Links --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $transactionSets->currentPage())
                                                <li class="page-item active" aria-current="page">
                                                    <a class="page-link" href="#">{{ $page }}</a>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link"
                                                        href="{{ $transactionSets->appends(['per_page' => request('per_page')])->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($transactionSets->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $transactionSets->appends(['per_page' => request('per_page')])->nextPageUrl() }}">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
