<?php

namespace App\Http\Controllers\Paiement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReponseController extends Controller
{
    public function cancel(){
        return view('paiement.cancel');

    }

    public function success(){
        return view('paiement.success');

    }
}
