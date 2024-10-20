<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AprioriResult;

class AprioriController extends Controller
{
    public function index()
    {
         // Ambil semua transaksi
         $transactions = Transaction::all();

         // Gabungkan transaksi berdasarkan tanggal dan buat itemsets
         $itemSets = $this->generateItemSets($transactions);

        return view('pages.apriori.index', compact('itemSets'));
    }

    public function process(Request $request)
    {
        $minSupport = $request->input('min_support');
        $minConfidence = $request->input('min_confidence');

        // Ambil semua transaksi
        $transactions = Transaction::all();

        // Gabungkan transaksi berdasarkan tanggal dan buat itemsets
        $itemSets = $this->generateItemSets($transactions);

        // Hitung support dan confidence
        $results = $this->apriori($itemSets, $minSupport, $minConfidence);

        return view('pages.apriori.results', compact('results', 'itemSets'));
    }

    private function generateItemSets($transactions)
    {
        $itemSets = [];

        // Gabungkan transaksi berdasarkan tanggal
        $groupedTransactions = $transactions->groupBy('transaction_date');

        // Loop melalui setiap transaksi yang sudah dikelompokkan berdasarkan tanggal
        foreach ($groupedTransactions as $date => $transactionsOnDate) {
            $itemSet = [];

            // Loop melalui setiap transaksi pada tanggal tersebut
            foreach ($transactionsOnDate as $transaction) {
                // Ambil kode menu dari transaksi
                $items = explode(',', $transaction->menu_code);
                foreach ($items as $item) {
                    $menu = Menu::where('code', $item)->first();
                    if ($menu) {
                        $itemSet[] = [
                            'name' => $menu->name,
                            'category' => $menu->category
                        ]; // Menyimpan nama dan kategori
                    }
                }
            }

            // Simpan itemSet yang terbentuk untuk tanggal ini
            $itemSets[] = [
                'date' => $date,
                'items' => $itemSet
            ];
        }

        return $itemSets;
    }

    private function isDifferentCategory($combo)
    {
        $categories = array_column($combo, 'category');
        return count($categories) === count(array_unique($categories)); // Periksa jika semua kategori berbeda
    }

    private function generateCombinations($items, $length)
    {
        $combinations = [];
        $this->combine([], $items, $length, $combinations);
        return $combinations;
    }
    
    private function combine($prefix, $items, $length, &$combinations)
    {
        if ($length == 0) {
            $combinations[] = $prefix;
            return;
        }
        for ($i = 0; $i < count($items); $i++) {
            $newPrefix = array_merge($prefix, [$items[$i]]);
            $this->combine($newPrefix, array_slice($items, $i + 1), $length - 1, $combinations);
        }
    }    

    private function calculateConfidence($combo, $supportCounts1, $supportCounts2, $supportCounts3, $supportCounts4, $totalTransactions, $minConfidence, $length)
    {
        $confidenceResults = [];
        $items = explode(',', $combo);

        // Hitung confidence untuk setiap pasangan aturan
        if ($length == 2) {
            // Logika confidence untuk 2-itemset
            foreach ($items as $itemA) {
                foreach ($items as $itemB) {
                    if ($itemA != $itemB) {
                        $supportAB = isset($supportCounts2[$combo]) ? $supportCounts2[$combo] / $totalTransactions : 0;
                        $supportA = isset($supportCounts1[$itemA]) ? $supportCounts1[$itemA] / $totalTransactions : 1;
                        $confidence = $supportAB / $supportA;

                        if ($confidence >= $minConfidence) {
                            $confidenceResults[] = "$itemB => $itemA: " . round($confidence, 2);
                        }
                    }
                }
            }
        } elseif ($length == 3) {
            // Logika confidence untuk 3-itemset
            foreach ($items as $itemA) {
                $otherItems = array_diff($items, [$itemA]);
                $supportABC = isset($supportCounts3[$combo]) ? $supportCounts3[$combo] / $totalTransactions : 0;
                $supportA = isset($supportCounts1[$itemA]) ? $supportCounts1[$itemA] / $totalTransactions : 1;
                $confidence = $supportABC / $supportA;
    
                if ($confidence >= $minConfidence) {
                    $confidenceResults[] = implode(',', $otherItems) . " => $itemA" . ": " . round($confidence, 2);
                }
            }
        } elseif ($length == 4) {
            // Logika confidence untuk 4-itemset
            foreach ($items as $itemA) {
                $otherItems = array_diff($items, [$itemA]);
                $supportABCD = isset($supportCounts4[$combo]) ? $supportCounts4[$combo] / $totalTransactions : 0;
                $supportA = isset($supportCounts1[$itemA]) ? $supportCounts1[$itemA] / $totalTransactions : 1;
                $confidence = $supportABCD / $supportA;
    
                if ($confidence >= $minConfidence) {
                    $confidenceResults[] = implode(',', $otherItems) . " => $itemA" . ": " . round($confidence, 2);
                }
            }
        }

        return $confidenceResults;
    }

    private function apriori($itemSets, $minSupport, $minConfidence)
    {
        $results = [];
        $totalTransactions = count($itemSets); // Total transaksi setelah digabung

        // Mengosongkan tabel apriori_results sebelum menyimpan data baru
        AprioriResult::truncate();

        // Hitung support untuk 1-itemset
        $supportCounts1 = [];
        foreach ($itemSets as $itemSet) {
            foreach ($itemSet['items'] as $item) {
                $itemName = $item['name'];
                if (!isset($supportCounts1[$itemName])) {
                    $supportCounts1[$itemName] = 0;
                }
                $supportCounts1[$itemName]++;
            }
        }

        // Simpan hasil 1-itemset
        $results['1-itemset'] = [];
        foreach ($supportCounts1 as $item => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 10) / 10; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                $result = [
                    'itemset' => [$item],
                    'support' => $support
                ];
    
                $results['1-itemset'][] = $result;
    
                // Simpan hasil 1-itemset ke database
                AprioriResult::create([
                    'itemset' => json_encode([$item]),
                    'support' => $support,
                    'confidence' => null
                ]);
            }
        }

        // Hitung support untuk 2-itemsets
        $supportCounts2 = [];
        foreach ($itemSets as $itemSet) {
            $combos = $this->generateCombinations($itemSet['items'], 2);
            foreach ($combos as $combo) {
                // Hanya lanjutkan jika semua kategori berbeda
                if ($this->isDifferentCategory($combo)) {
                    $comboKey = implode(',', array_column($combo, 'name'));
                    if (!isset($supportCounts2[$comboKey])) {
                        $supportCounts2[$comboKey] = 0;
                    }
                    $supportCounts2[$comboKey]++;
                }
            }
        }

        // Simpan hasil 2-itemsets
        $results['2-itemsets'] = [];
        foreach ($supportCounts2 as $combo => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 10) / 10; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                $confidence = $this->calculateConfidence($combo, $supportCounts1, $supportCounts2, [], [], $totalTransactions, $minConfidence, 2);

                $result = [
                    'itemset' => explode(',', $combo),
                    'support' => $support,
                    'confidence' => $confidence
                ];

                $results['2-itemsets'][] = $result;

                // Simpan hasil 2-itemset ke database
                AprioriResult::create([
                    'itemset' => json_encode(explode(',', $combo)),
                    'support' => $support,
                    'confidence' => json_encode($confidence)
                ]);
            }
        }

        // Hitung support untuk 3-itemsets
        $supportCounts3 = [];
        foreach ($itemSets as $itemSet) {
            $combos = $this->generateCombinations($itemSet['items'], 3);
            foreach ($combos as $combo) {
                // Hanya lanjutkan jika semua kategori berbeda
                if ($this->isDifferentCategory($combo)) {
                    $comboKey = implode(',', array_column($combo, 'name'));
                    if (!isset($supportCounts3[$comboKey])) {
                        $supportCounts3[$comboKey] = 0;
                    }
                    $supportCounts3[$comboKey]++;
                }
            }
        }

        // Simpan hasil 3-itemsets
        $results['3-itemsets'] = [];
        foreach ($supportCounts3 as $combo => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 10) / 10; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                $confidence = $this->calculateConfidence($combo, $supportCounts1, $supportCounts2, $supportCounts3, [], $totalTransactions, $minConfidence, 3);

                $result = [
                    'itemset' => explode(',', $combo),
                    'support' => $support,
                    'confidence' => $confidence
                ];

                $results['3-itemsets'][] = $result;

                // Simpan hasil 3-itemset ke database
                AprioriResult::create([
                    'itemset' => json_encode(explode(',', $combo)),
                    'support' => $support,
                    'confidence' => json_encode($confidence)
                ]);
            }
        }

        // Hitung support untuk 4-itemsets
        $supportCounts4 = [];
        foreach ($itemSets as $itemSet) {
            $combos = $this->generateCombinations($itemSet['items'], 4);
            foreach ($combos as $combo) {
                // Hanya lanjutkan jika semua kategori berbeda
                if ($this->isDifferentCategory($combo)) {
                    $comboKey = implode(',', array_column($combo, 'name'));
                    if (!isset($supportCounts4[$comboKey])) {
                        $supportCounts4[$comboKey] = 0;
                    }
                    $supportCounts4[$comboKey]++;
                }
            }
        }

        // Simpan hasil 4-itemsets
        $results['4-itemsets'] = [];
        foreach ($supportCounts4 as $combo => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 10) / 10; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                $confidence = $this->calculateConfidence($combo, $supportCounts1, $supportCounts2, $supportCounts3, $supportCounts4, $totalTransactions, $minConfidence, 4);

                $result = [
                    'itemset' => explode(',', $combo),
                    'support' => $support,
                    'confidence' => $confidence
                ];

                $results['4-itemsets'][] = $result;

                // Simpan hasil 4-itemset ke database
                AprioriResult::create([
                    'itemset' => json_encode(explode(',', $combo)),
                    'support' => $support,
                    'confidence' => json_encode($confidence)
                ]);
            }
        }

        return $results;
    }

}
