<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Menampilkan daftar transaksi
    public function index()
    {
        $transactions = Transaction::with('menu')->get();
        
        return view('transactions.index', compact('transactions'));
    }

    // Menampilkan form untuk menambah transaksi
    public function create()
    {
        return view('transactions.create');
    }

    // Menyimpan transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'menu_code' => 'required|max:10',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        Transaction::create($request->all());

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // Menampilkan form untuk mengedit transaksi
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('transactions.edit', compact('transaction'));
    }

    // Memperbarui transaksi
    public function update(Request $request, $id)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'menu_code' => 'required|max:10',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $transaction = Transaction::findOrFail($id);
        $transaction->update($request->all());

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    // Menghapus transaksi
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
