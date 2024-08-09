<?php

namespace App\Http\Controllers;

use App\Precome;

class PrecomeController extends Controller
{
    public function index()
    {
        $precome = Precome::orderBy('total_eur', 'desc')->get();

        return view('precome.index', compact('precome'));
    }

    public function edit(Precome $precome)
    {
        return view('precome.edit', compact('precome'));
    }

}
