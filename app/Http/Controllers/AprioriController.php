<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Menu;

class AprioriController extends Controller
{
    public function index()
    {
        return view('apriori.index');
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

        return view('apriori.results', compact('results', 'itemSets'));
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
                        $itemSet[] = $menu->name; // Berdasarkan kategori
                    }
                }
            }

            // Simpan itemSet yang terbentuk untuk tanggal ini
            $itemSets[] = array_unique($itemSet); // Hilangkan duplikasi item
        }

        return $itemSets;
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

    private function calculateConfidence($combo, $supportCounts1, $supportCounts2, $totalTransactions, $minConfidence)
    {
        $confidenceResults = [];
        $items = explode(',', $combo);

        // Hitung confidence untuk setiap pasangan aturan
        foreach ($items as $itemA) {
            foreach ($items as $itemB) {
                if ($itemA != $itemB) {
                    $supportAB = isset($supportCounts2[$combo]) ? $supportCounts2[$combo] / $totalTransactions : 0;
                    $supportA = isset($supportCounts1[$itemA]) ? $supportCounts1[$itemA] / $totalTransactions : 1;
                    $confidence = $supportAB / $supportA;

                    if ($confidence >= $minConfidence) {
                        $confidenceResults[] = "$itemA => $itemB: " . round($confidence, 2);
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

        // Hitung support untuk 1-itemset
        $supportCounts1 = [];
        foreach ($itemSets as $items) {
            foreach ($items as $item) {
                if (!isset($supportCounts1[$item])) {
                    $supportCounts1[$item] = 0;
                }
                $supportCounts1[$item]++;
            }
        }

        // Simpan hasil 1-itemset
        $results['1-itemset'] = [];
        foreach ($supportCounts1 as $item => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 10) / 10; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                $results['1-itemset'][] = [
                    'itemset' => [$item],
                    'support' => $support
                ];
            }
        }

        // Hitung support untuk 2-itemsets
        $supportCounts2 = [];
        foreach ($itemSets as $items) {
            $combos = $this->generateCombinations($items, 2);
            foreach ($combos as $combo) {
                $comboKey = implode(',', $combo);
                if (!isset($supportCounts2[$comboKey])) {
                    $supportCounts2[$comboKey] = 0;
                }
                $supportCounts2[$comboKey]++;
            }
        }

        // Simpan hasil 2-itemsets
        $results['2-itemsets'] = [];
        foreach ($supportCounts2 as $combo => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 10) / 10; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                $results['2-itemsets'][] = [
                    'itemset' => explode(',', $combo),
                    'support' => $support,
                    'confidence' => $this->calculateConfidence($combo, $supportCounts1, $supportCounts2, $totalTransactions, $minConfidence)
                ];
            }
        }

        // Hitung support untuk 3-itemsets
        $supportCounts3 = [];
        foreach ($itemSets as $items) {
            $combos = $this->generateCombinations($items, 3);
            foreach ($combos as $combo) {
                $comboKey = implode(',', $combo);
                if (!isset($supportCounts3[$comboKey])) {
                    $supportCounts3[$comboKey] = 0;
                }
                $supportCounts3[$comboKey]++;
            }
        }

        // Simpan hasil 3-itemsets
        $results['3-itemsets'] = [];
        foreach ($supportCounts3 as $combo => $count) {
            $support = $count / $totalTransactions;
            $support = ceil($support * 10) / 10; // Bulatkan ke atas dengan 1 desimal
            if ($support >= $minSupport) {
                $results['3-itemsets'][] = [
                    'itemset' => explode(',', $combo),
                    'support' => $support,
                    'confidence' => $this->calculateConfidence($combo, $supportCounts1, $supportCounts2, $totalTransactions, $minConfidence)
                ];
            }
        }

        return $results;
    }

}
