<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AprioriResult;
use App\Models\TransactionSet;

class AprioriController extends Controller
{
    public function index(Request $request)
    {
        TransactionSet::truncate();

        $transactions = Transaction::all();
        $itemSets = $this->generateItemSets($transactions);
    
        $perPage = $request->get('per_page', 10);
        $transactionSets = TransactionSet::paginate($perPage);

        return view('pages.apriori.index', compact('transactionSets'));
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

        $results = AprioriResult::all();

        // Organize results by itemset length
        $organizedResults = [
            '1-itemset' => [],
            '2-itemsets' => [],
            '3-itemsets' => [],
            '4-itemsets' => [],
        ];

        foreach ($results as $result) {
            $items = json_decode($result->items);
            $itemCount = count($items);

            // Group results based on itemset length
            if ($itemCount == 1) {
                $organizedResults['1-itemset'][] = [
                    'itemset' => $items,
                    'support' => $result->support,
                ];
            } elseif ($itemCount == 2) {
                $organizedResults['2-itemsets'][] = [
                    'itemset' => $items,
                    'support' => $result->support,
                    'confidence' => [
                        [
                            'confidence' => $result->confidence,
                            'recommendation' => $result->recommendation,
                        ],
                    ],
                    'lift' => $result->lift,
                ];
            } elseif ($itemCount == 3) {
                $organizedResults['3-itemsets'][] = [
                    'itemset' => $items,
                    'support' => $result->support,
                    'confidence' => [
                        [
                            'confidence' => $result->confidence,
                            'recommendation' => $result->recommendation,
                        ],
                    ],
                    'lift' => $result->lift,
                ];
            } elseif ($itemCount == 4) {
                $organizedResults['4-itemsets'][] = [
                    'itemset' => $items,
                    'support' => $result->support,
                    'confidence' => [
                        [
                            'confidence' => $result->confidence,
                            'recommendation' => $result->recommendation,
                        ],
                    ],
                    'lift' => $result->lift,
                ];
            }
        }


        return view('pages.apriori.results', [
            'results' => $organizedResults,
        ]);
    }

    private function generateItemSets($transactions)
    {
        $itemSets = [];

        // Gabungkan transaksi berdasarkan tanggal
        $groupedTransactions = $transactions->groupBy('transaction_date');

        // Loop melalui setiap transaksi yang sudah dikelompokkan berdasarkan tanggal
        foreach ($groupedTransactions as $date => $transactionsOnDate) {
            $itemSet = [];
            $itemName = [];

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

                        $itemName[] = $menu->name;
                    }
                }
            }

            $itemSetString = implode(', ', $itemName);

            if (!empty($itemSetString)) {
                TransactionSet::create([
                    'transaction_date' => $date,
                    'itemset' => $itemSetString
                ]);
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
                        $confidence = ceil($confidence * 100) / 100;

                        if ($confidence >= $minConfidence) {
                            $confidenceResults[] = [
                                'items' => [$itemA, $itemB],
                                'recommendation' => $itemB,
                                'confidence' => $confidence
                            ];
                        }
                    }
                }
            }
        } elseif ($length == 3) {
            // Logika confidence untuk 3-itemset
            foreach ($items as $itemA) {
                $otherItems = array_diff($items, [$itemA]);

                if (count($otherItems) == 2) {
                    $otherItemsKey = implode(',', $otherItems);
                    $comboKey = implode(',', $items);
            
                    $supportABC = isset($supportCounts3[$comboKey]) ? $supportCounts3[$comboKey] / $totalTransactions : 0;
                    $supportAB = isset($supportCounts2[$otherItemsKey]) ? $supportCounts2[$otherItemsKey] / $totalTransactions : 1;
                    $confidence = $supportABC / $supportAB;
        
                    if ($confidence >= $minConfidence) {
                        $confidenceResults[] = [
                            'items' => array_merge($otherItems, [$itemA]),
                            'recommendation' => $itemA,
                            'confidence' => $confidence
                        ];
                    }
                }
            }
        } elseif ($length == 4) {
            // Logika confidence untuk 4-itemset
            foreach ($items as $itemA) {
                $otherItems = array_diff($items, [$itemA]);

                if (count($otherItems) == 3) {
                    $otherItemsKey = implode(',', $otherItems);
                    $comboKey = implode(',', $items);
            
                    $supportABCD = isset($supportCounts4[$comboKey]) ? $supportCounts4[$comboKey] / $totalTransactions : 0;
                    $supportABC = isset($supportCounts3[$otherItemsKey]) ? $supportCounts3[$otherItemsKey] / $totalTransactions : 1;
                    $confidence = $supportABCD / $supportABC;
        
                    if ($confidence >= $minConfidence) {
                        $confidenceResults[] = [
                            'items' => array_merge($otherItems, [$itemA]),
                            'recommendation' => $itemA,
                            'confidence' => $confidence
                        ];
                    }
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
        $supportCounts2 = [];
        $supportCounts3 = [];
        $supportCounts4 = [];
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
        foreach ($supportCounts1 as $item => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 100) / 100; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                // Simpan hasil 1-itemset ke database
                AprioriResult::create([
                    'items' => json_encode([$item]),
                    'recommendation' => null,
                    'support' => $support,
                    'confidence' => null,
                    'lift' => null
                ]);
            }
        }

        // Hitung support dan simpan hasil untuk 2-itemsets, 3-itemsets, dan 4-itemsets
        for ($length = 2; $length <= 4; $length++) {
            $supportCounts = [];
            foreach ($itemSets as $itemSet) {
                $combos = $this->generateCombinations($itemSet['items'], $length);
                foreach ($combos as $combo) {
                    if ($this->isDifferentCategory($combo)) {
                        $comboKey = implode(',', array_column($combo, 'name'));
                        if (!isset($supportCounts[$comboKey])) {
                            $supportCounts[$comboKey] = 0;
                        }
                        $supportCounts[$comboKey]++;
                    }
                }
            }

            // Simpan hasil untuk itemsets sesuai panjang
            foreach ($supportCounts as $combo => $count) {
                $support = $count / $totalTransactions;
                $support = ceil($support * 100) / 100;
                if ($support >= $minSupport) {
                    // Hitung confidence
                    if ($length == 2) {
                        $confidenceResults = $this->calculateConfidence(
                            $combo,
                            $supportCounts1,
                            $supportCounts,
                            [],
                            [],
                            $totalTransactions,
                            $minConfidence,
                            $length
                        );
                    } elseif ($length == 3) {
                        $confidenceResults = $this->calculateConfidence(
                            $combo,
                            $supportCounts1,
                            $supportCounts2,
                            $supportCounts,
                            [],
                            $totalTransactions,
                            $minConfidence,
                            $length
                        );
                    } elseif ($length == 4) {
                        $confidenceResults = $this->calculateConfidence(
                            $combo,
                            $supportCounts1,
                            $supportCounts2,
                            $supportCounts3,
                            $supportCounts,
                            $totalTransactions,
                            $minConfidence,
                            $length
                        );
                    }
                    
                    // Simpan hasil ke database dan hitung lift
                    foreach ($confidenceResults as $result) {
                        $items = is_array($result['items']) ? $result['items'] : json_decode($result['items'], true);
                        
                        $antecedentItems = $items;
                        $antecedentCount = count($antecedentItems);
                    
                        if ($antecedentCount > 1) {
                            $firstItem = $antecedentItems[0];
                            $secondItem = $antecedentItems[1];
                    
                            $supportB = isset($supportCounts1[$secondItem]) ? $supportCounts1[$secondItem] / $totalTransactions : 0;
                    
                            $lift = $supportB > 0 ? ($result['confidence'] / $supportB) : 0;
                            
                            AprioriResult::create([
                                'items' => json_encode($items),
                                'recommendation' => $result['recommendation'],
                                'confidence' => $result['confidence'],
                                'support' => $support,
                                'lift' => $lift
                            ]);
                        }
                    }                    
                }
            }
        }

        return $results;
    }

}
