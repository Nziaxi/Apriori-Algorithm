<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AprioriResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        // Ambil data menu yang dipilih
        $menusWithQuantity = array_filter($request->input('selected_menus', []), function ($quantity) {
            return $quantity > 0;
        });
        
        $selectedMenus = array_keys($menusWithQuantity);

        // Ambil data kategori dari menu yang dipilih
        $menuCategories = DB::table('menus')
            ->whereIn('name', $selectedMenus)
            ->pluck('category')
            ->unique()
            ->toArray();

        // Buat kombinasi menu dengan kategori yang berbeda
        $menuCombinations = $this->generateMenuCombinations($selectedMenus, $menuCategories);

        $recommendations = [];
        $aprioriResults = AprioriResult::all();

        // Bandingkan kombinasi menu yang dihasilkan dengan field pick_items
        foreach ($aprioriResults as $index => $result) {
            $pickItems = json_decode($result->pick_items);

            if ($pickItems === null) {
                continue;
            }

            // Cek jika hanya satu menu yang dipilih
            if (count($selectedMenus) === 1) {
                $singleMenu = $selectedMenus;
                if ($singleMenu == $pickItems) {
                    // Define $recommendation here
                    $recommendation = [
                        'recommendation' => $result->recommendation,
                        'confidence' => $result->confidence,
                    ];

                    // Simpan hanya recommendation dengan confidence tertinggi
                    if (!isset($recommendations[$result->recommendation]) || $recommendations[$result->recommendation]['confidence'] < $result->confidence) {
                        $recommendations[$result->recommendation] = $recommendation;
                    }
                }
            } else {
                foreach ($selectedMenus as $menu) {
                    if ($pickItems === [$menu]) {
                        // Define $recommendation here
                        $recommendation = [
                            'recommendation' => $result->recommendation,
                            'confidence' => $result->confidence,
                        ];

                        // Simpan hanya recommendation dengan confidence tertinggi
                        if (!isset($recommendations[$result->recommendation]) || $recommendations[$result->recommendation]['confidence'] < $result->confidence) {
                            $recommendations[$result->recommendation] = $recommendation;
                        }
                    }
                }

                // Cek jika kombinasi menu ada dalam pick_items
                foreach ($menuCombinations as $combination) {
                    sort($combination);
                    sort($pickItems);

                    if ($combination === $pickItems) {
                        // Define $recommendation here
                        $recommendation = [
                            'recommendation' => $result->recommendation,
                            'confidence' => $result->confidence,
                        ];

                        // Simpan hanya recommendation dengan confidence tertinggi
                        if (!isset($recommendations[$result->recommendation]) || $recommendations[$result->recommendation]['confidence'] < $result->confidence) {
                            $recommendations[$result->recommendation] = $recommendation;
                        }
                    }
                }
            }
        }

        // Urutkan rekomendasi berdasarkan confidence terbesar ke terkecil
        $recommendations = array_values($recommendations);
        usort($recommendations, function ($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });

        return view('pages.menu-services.recommendations', compact('recommendations', 'selectedMenus', 'menusWithQuantity'));
    }

    private function generateMenuCombinations(array $selectedMenus, array $menuCategories)
    {
        // Menghasilkan kombinasi menu berdasarkan kategori yang berbeda
        $combinations = [];
        $count = count($selectedMenus);

        for ($i = 0; $i < (1 << $count); $i++) {
            $combination = [];
            $currentCategories = [];

            for ($j = 0; $j < $count; $j++) {
                if ($i & (1 << $j)) {
                    $combination[] = $selectedMenus[$j];

                    // Ambil kategori dari menu yang dipilih
                    $categoryId = DB::table('menus')->where('name', $selectedMenus[$j])->value('category');
                    $currentCategories[] = $categoryId;
                }
            }

            // Tambahkan ke kombinasi jika kategori yang berbeda
            if (count(array_unique($currentCategories)) === count($currentCategories) && count($combination) > 1) {
                $combinations[] = $combination;
            }
        }

        return $combinations;
    }

    // Menangani konfirmasi pesanan
    public function confirmOrder(Request $request)
    {
        $selectedMenus = $request->input('selected_menus', []);
        $orderedItems = [];
        $totalAmount = 0;

        foreach ($selectedMenus as $menuName => $quantity) {
            if ($quantity > 0) {
                $menu = Menu::where('name', $menuName)->first();
                if ($menu) {
                    $total = $menu->price * $quantity;
                    $orderedItems[] = [
                        'name' => $menuName,
                        'price' => $menu->price,
                        'quantity' => $quantity,
                        'total' => $total
                    ];
                    $totalAmount += $total;
                }
            }
        }

        // Simpan detail pesanan dan total amount di session
        Session::put('orderDetails', $orderedItems);
        Session::put('totalAmount', $totalAmount);

        return redirect()->route('show.invoice');
    }

    // Tampilkan halaman invoice
    public function showInvoice()
    {
        $orderDetails = Session::get('orderDetails', []);
        $totalAmount = Session::get('totalAmount', 0);

        return view('pages.menu-services.invoice', compact('orderDetails', 'totalAmount'));
    }
}
