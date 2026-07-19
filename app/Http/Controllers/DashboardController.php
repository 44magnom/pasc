<?php

namespace App\Http\Controllers;
 use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
   

public function index()
{
    $matieres = Auth::user()->matieres()->latest()->get();

    return view('back.dashboard', compact('matieres'));
}
}
