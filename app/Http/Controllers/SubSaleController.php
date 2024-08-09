<?php

namespace App\Http\Controllers;

use App\Sale;
use App\SubSale;
use Illuminate\Http\Request;

class SubSaleController extends Controller
{

    public function store(Request $request)
    {
        $sale = Sale::where('id',  $request->get('sale_id'))->first();
        SubSale::create([
            'sale_id'=> $request->get('sale_id'),
            'name' => $request->get('subsalename'),
            'price' => $request->get('price')
        ]);

        return redirect()->route('sales.edit', $sale)
            ->with('success', 'New agreed task added');
    }

    public function update(Request $request, SubSale $subSale)
    {
        $subSale->complete = $request->completed;
        $subSale->save();
    }

}
