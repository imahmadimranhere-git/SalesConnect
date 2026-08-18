<?php

namespace App\Http\Controllers\Shopkeeper;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $shop = $user->shop;

        return view('shopkeeper.profile', compact('user', 'shop'));
    }
}