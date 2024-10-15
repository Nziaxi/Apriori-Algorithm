<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\AprioriResult;

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

    // Menampilkan select menu
    public function selectMenu()
    {
        $menus = Menu::all();
        return view('menu-services.index', compact('menus'));
    }

    // Validasi menu yang dipilih
    public function validateMenu(Request $request)
    {
        // Ambil menu yang dipilih
        $selectedMenus = $request->input('selected_menus');
        
        // Ambil hasil Apriori dari database
        $aprioriResults = AprioriResult::all();
        
        $recommendations = [];
        
        // Ambil kategori dari menu yang dipilih
        $selectedMenuItems = Menu::whereIn('name', $selectedMenus)->get();
        $selectedCategories = $selectedMenuItems->pluck('category')->unique()->toArray(); // Ambil kategori unik dari menu yang dipilih

        // Perulangan melalui setiap hasil Apriori
        foreach ($aprioriResults as $result) {
            // Ambil itemset dari hasil dan ubah ke dalam array
            $itemset = json_decode($result->itemset, true); // Decode JSON ke array
            
            // Cek apakah ada item dalam itemset yang cocok dengan menu yang dipilih
            if (array_intersect($selectedMenus, $itemset)) {
                // Rekomendasikan item lain dalam itemset yang belum dipilih
                $recommendedItems = array_diff($itemset, $selectedMenus);
                
                // Filter rekomendasi berdasarkan kategori yang berbeda
                foreach ($recommendedItems as $item) {
                    $menuItem = Menu::where('name', $item)->first();
                    
                    if ($menuItem && !in_array($menuItem->category, $selectedCategories) && !in_array($item, $recommendations)) {
                        // Pastikan item dari kategori yang berbeda dan tidak duplikat
                        $recommendations[] = $item;
                    }
                }
            }
        }

        return view('menu-services.validate-menu', compact('selectedMenus', 'recommendations'));
    }

    // Menangani konfirmasi pesanan
    public function confirmOrder(Request $request)
    {
        // Ambil menu yang dipilih dari form konfirmasi
        $selectedMenusInput = $request->input('selected_menus');

        // Pastikan $selectedMenusInput adalah array
        $selectedMenus = [];
        if (is_array($selectedMenusInput)) {
            foreach ($selectedMenusInput as $inputString) {
                // Pisahkan string menggunakan explode dan gabungkan hasilnya ke $selectedMenus
                $selectedMenus = array_merge($selectedMenus, explode(',', $inputString));
            }
        }
        
        $recommendedMenus = $request->input('recommended_menus'); // Menu rekomendasi yang ditambahkan
        
        // Gabungkan menu yang dipilih dan rekomendasi hanya jika ada rekomendasi
        if (!empty($recommendedMenus)) {
            $finalOrder = array_merge($selectedMenus, $recommendedMenus);
        } else {
            $finalOrder = $selectedMenus;
        }
        
        // Simpan pesanan ke dalam session
        session(['order' => $finalOrder]);

        return view('menu-services.confirm-order');
    }

}
