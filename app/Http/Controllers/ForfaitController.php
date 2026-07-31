<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forfait;
use App\Models\Abonnement;
use Illuminate\Support\Facades\Auth;


class ForfaitController extends Controller
{

public function index()
{
    $forfaits = Forfait::where('is_active', true)->get();

    $abonnement = Abonnement::where('user_id', Auth::id())

        ->latest()
        ->first();

    return view('forfaits.index', compact('forfaits', 'abonnement'));
}
    public function show(Forfait $forfait)
{
    return view('forfaits.show', compact('forfait'));
}
}
