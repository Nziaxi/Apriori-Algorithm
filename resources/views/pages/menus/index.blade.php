@extends('layout.master')

@section('title')
    Data Menu
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Data Menu</li>
@endsection

@section('content')
    <section class="section">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title">Data Menu</h5>
                        <div class="d-flex align-items-center gap-1">
                            <a href="{{ route('menus.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#importModal">Import</button>
                            <a href="#" class="btn btn-secondary btn-sm">Print</a>
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert @if (session('action') == 'add') alert-success 
                            @elseif (session('action') == 'edit') alert-info 
                            @elseif (session('action') == 'delete') alert-danger @endif alert-dismissible fade show"
                            role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('menus.index') }}" class="mb-3">
                        <label for="perPage">Tampilkan entri: </label>
                        <select name="per_page" id="perPage" onchange="this.form.submit()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                        </select>
                    </form>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <div class="d-flex justify-content-between">
                                        Kode Menu
                                        <a style="color: black;"
                                            href="{{ route('menus.index', ['sort_by' => 'code', 'sort_direction' => $sortColumn === 'code' && $sortDirection === 'asc' ? 'desc' : 'asc', 'per_page' => request('per_page')]) }}">
                                            @if ($sortColumn === 'code')
                                                @if ($sortDirection === 'asc')
                                                    <i class="ri-sort-asc"></i>
                                                @else
                                                    <i class="ri-sort-desc"></i>
                                                @endif
                                            @else
                                                <i class="ri-sort-asc"></i>
                                            @endif
                                        </a>
                                    </div>
                                </th>
                                <th scope="col">
                                    <div class="d-flex justify-content-between">
                                        Nama Menu
                                        <a style="color: black;"
                                            href="{{ route('menus.index', ['sort_by' => 'name', 'sort_direction' => $sortColumn === 'name' && $sortDirection === 'asc' ? 'desc' : 'asc', 'per_page' => request('per_page')]) }}">
                                            @if ($sortColumn === 'name')
                                                @if ($sortDirection === 'asc')
                                                    <i class="ri-sort-asc"></i>
                                                @else
                                                    <i class="ri-sort-desc"></i>
                                                @endif
                                            @else
                                                <i class="ri-sort-asc"></i>
                                            @endif
                                        </a>
                                    </div>
                                </th>
                                <th scope="col">
                                    <div class="d-flex justify-content-between">
                                        Kategori
                                        <a style="color: black;"
                                            href="{{ route('menus.index', ['sort_by' => 'category', 'sort_direction' => $sortColumn === 'category' && $sortDirection === 'asc' ? 'desc' : 'asc', 'per_page' => request('per_page')]) }}">
                                            @if ($sortColumn === 'category')
                                                @if ($sortDirection === 'asc')
                                                    <i class="ri-sort-asc"></i>
                                                @else
                                                    <i class="ri-sort-desc"></i>
                                                @endif
                                            @else
                                                <i class="ri-sort-asc"></i>
                                            @endif
                                        </a>
                                    </div>
                                </th>
                                <th scope="col">
                                    <div class="d-flex justify-content-between">
                                        Harga
                                        <a style="color: black;"
                                            href="{{ route('menus.index', ['sort_by' => 'price', 'sort_direction' => $sortColumn === 'price' && $sortDirection === 'asc' ? 'desc' : 'asc', 'per_page' => request('per_page')]) }}">
                                            @if ($sortColumn === 'price')
                                                @if ($sortDirection === 'asc')
                                                    <i class="ri-sort-asc"></i>
                                                @else
                                                    <i class="ri-sort-desc"></i>
                                                @endif
                                            @else
                                                <i class="ri-sort-asc"></i>
                                            @endif
                                        </a>
                                    </div>
                                </th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($menus->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @else
                                @foreach ($menus as $menu)
                                    <tr>
                                        <td>{{ $menu->code }}</td>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ $menu->category }}</td>
                                        <td>Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-warning">Edit</a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $menu->id }}">Hapus</button>

                                            {{-- Delete Modal --}}
                                            <div class="modal fade" id="deleteModal{{ $menu->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Hapus Data?</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus data ({{ $menu->code }} --
                                                            {{ $menu->name }})
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form action="{{ route('menus.destroy', $menu->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between">
                        <p>Menampilkan {{ $menus->firstItem() }} hingga {{ $menus->lastItem() }} dari
                            {{ $menus->total() }} entri
                        </p>

                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                {{-- Previous Page Link --}}
                                @if ($menus->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $menus->appends(['per_page' => request('per_page')])->previousPageUrl() }}">Previous</a>
                                    </li>
                                @endif

                                {{-- Pagination Links --}}
                                @foreach ($menus->links()->elements as $element)
                                    {{-- Array Of Links --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $menus->currentPage())
                                                <li class="page-item active" aria-current="page"><a class="page-link"
                                                        href="#">{{ $page }}</a></li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $menus->appends(['per_page' => request('per_page')])->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($menus->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $menus->appends(['per_page' => request('per_page')])->nextPageUrl() }}">Next</a>
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

        {{-- Import Modal --}}
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">Pilih file Excel</label>
                                <input class="form-control" type="file" id="file" name="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
@endsection
