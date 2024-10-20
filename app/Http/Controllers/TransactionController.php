<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Menampilkan daftar transaksi
    public function index(Request $request)
    {
        // Ambil parameter sorting dari query
        $sortColumn = $request->input('sort_by', 'transaction_date');
        $sortDirection = $request->input('sort_direction', 'asc');

        $perPage = $request->input('per_page', 10);

        // Melakukan join dengan table menu
        $transactions = \App\Models\Transaction::with('menu')
            ->join('menus', 'transactions.menu_code', '=', 'menus.code')
            ->orderBy($sortColumn === 'name' ? 'menus.name' : $sortColumn, $sortDirection)
            ->select('transactions.*')
            ->paginate($perPage);
        
        return view('pages.transactions.index', compact('transactions', 'sortColumn', 'sortDirection'));
    }

    // Menampilkan form untuk menambah transaksi
    public function create()
    {
        $menus = Menu::all();

        return view('pages.transactions.create', compact('menus'));
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

        // Mengatur session untuk pesan sukses
        session()->flash('success', 'Data berhasil ditambahkan!');
        session()->flash('action', 'add');

        return redirect()->route('transactions.index');
    }

    // Menampilkan form untuk mengedit transaksi
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $menus = Menu::all();

        return view('pages.transactions.edit', compact('transaction', 'menus'));
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

        // Mengatur session untuk pesan sukses
        session()->flash('success', 'Data berhasil diperbarui!');
        session()->flash('action', 'edit');

        return redirect()->route('transactions.index');
    }

    // Menghapus transaksi
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        // Mengatur session untuk pesan sukses
        session()->flash('success', 'Data berhasil dihapus!');
        session()->flash('action', 'delete');

        return redirect()->route('transactions.index');
    }
}
