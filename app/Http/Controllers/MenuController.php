<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Menampilkan daftar menu
    public function index()
    {
        $menus = Menu::all();
        return view('menus.index', compact('menus'));
    }

    // Menampilkan form untuk menambah menu
    public function create()
    {
        return view('menus.create');
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

        return redirect()->route('menus.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit menu
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('menus.edit', compact('menu'));
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

        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    // Menghapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus!');
    }
}
