@extends('layout.master')

@section('title')
    Pilih Menu
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">Pilih Menu</li>
@endsection

@section('content')
    <section class="section">
        <div class="row d-flex justify-content-center align-items-center">

            <div class="card w-75">
                <div class="card-body">
                    <form action="/validate-menu" method="POST">
                        @csrf
                        <h5 class="card-title">Pilih Menu</h5>
                        <div class="row">
                            @foreach ($menus as $menu)
                                <div class="col-4 form-check">
                                    <input class="form-check-input" type="checkbox" name="selected_menus[]"
                                        value="{{ $menu->name }}">
                                    <label class="form-check-label">
                                        {{ $menu->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
@endsection
