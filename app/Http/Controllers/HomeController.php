<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $menuCount = Menu::count();
        $transactionCount = Transaction::count();
        $userCount = User::count();

        return view('pages.home', compact('menuCount', 'transactionCount', 'userCount'));
    }
}
