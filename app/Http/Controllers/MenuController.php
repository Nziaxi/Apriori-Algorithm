<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Menampilkan daftar menu
    public function index(Request $request)
    {
        // Ambil parameter sorting dari query
        $sortColumn = $request->input('sort_by', 'code');
        $sortDirection = $request->input('sort_direction', 'asc');

        $perPage = $request->input('per_page', 10);

        // Dapatkan data dengan sorting
        $menus = Menu::orderBy($sortColumn, $sortDirection)->paginate($perPage);
        
        return view('pages.menus.index', compact('menus', 'sortColumn', 'sortDirection'));
    }

    // Menampilkan form untuk menambah menu
    public function create()
    {
        return view('pages.menus.create');
    }

    // Menyimpan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:menus|max:10',
            'name' => 'required|max:255',
            'category' => 'required|max:50',
        ]);

        Menu::create($request->all());

        // Mengatur session untuk pesan sukses
        session()->flash('success', 'Data berhasil ditambahkan!');
        session()->flash('action', 'add');

        return redirect()->route('menus.index');
    }

    // Menampilkan form untuk mengedit menu
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('pages.menus.edit', compact('menu'));
    }

    // Memperbarui menu
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:10|unique:menus,code,' . $id,
            'name' => 'required|max:255',
            'category' => 'required|max:50',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update($request->all());

        // Mengatur session untuk pesan sukses
        session()->flash('success', 'Data berhasil diperbarui!');
        session()->flash('action', 'edit');

        return redirect()->route('menus.index');
    }

    // Menghapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        // Mengatur session untuk pesan sukses
        session()->flash('success', 'Data berhasil dihapus!');
        session()->flash('action', 'delete');

        return redirect()->route('menus.index');
    }

}
