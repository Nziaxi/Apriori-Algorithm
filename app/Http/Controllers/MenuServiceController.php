<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Models\AprioriResult;

class MenuServiceController extends Controller
{
    // Menampilkan select menu
    public function selectMenu()
    {
        $menus = Menu::all();
        return view('pages.menu-services.index', compact('menus'));
    }

    // Validasi menu yang dipilih
    public function validateMenu(Request $request)
    {
        $request->validate([
            'selected_menus' => 'required|array|min:1',
            'selected_menus.*' => 'string',
        ], [
            'selected_menus.required' => 'Silakan pilih setidaknya satu menu.',
            'selected_menus.min' => 'Silakan pilih setidaknya satu menu.',
        ]);

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
            $itemset = json_decode($result->items, true); // Decode JSON ke array
            
            // Cek apakah ada item dalam itemset yang cocok dengan menu yang dipilih
            if (array_intersect($selectedMenus, $itemset)) {
                // Rekomendasikan item lain dalam itemset yang belum dipilih
                $recommendedItems = array_diff($itemset, $selectedMenus);
                
                // Filter rekomendasi berdasarkan kategori yang berbeda
                foreach ($recommendedItems as $item) {
                    $menuItem = Menu::where('name', $item)->first();
                    
                    if ($menuItem && !in_array($menuItem->category, $selectedCategories) && !in_array($item, array_column($recommendations, 'name'))) {
                        // Pastikan item dari kategori yang berbeda dan tidak duplikat
                        $recommendations[] = [
                            'name' => $item,
                            'confidence' => $result->confidence * 100, // Ambil nilai confidence dari hasil Apriori
                        ];
                    }
                }
            }
        }

        // Urutkan rekomendasi berdasarkan confidence dari terbesar ke terkecil
        usort($recommendations, function ($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });

        return view('pages.menu-services.validate-menu', compact('selectedMenus', 'recommendations'));
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
        $finalOrder = array_merge($selectedMenus, $recommendedMenus ?? []);
        
        // Simpan pesanan ke dalam session
        session(['order' => $finalOrder]);
        
        return view('pages.menu-services.confirm-order');
    }
}
