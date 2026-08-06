<?php

namespace App\Http\Controllers;
 use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   

public function index()
{
    $user = Auth::user();

    $matieres = $user->matieres()
        ->orderBy('matiere', 'asc')
        ->get();

    return view('back.dashboard', compact('matieres', 'user'));
}
}
