<?php

namespace App\Http\Controllers;

use App\Agreement;
use App\AgreementGroup;
use App\Http\Requests\CreateAgreementGroupRequest;
use App\Sale;
use Illuminate\Http\Request;

class AgreementGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create(Request $request, Sale $sale)
    {
        $sales = Sale::active()->get();
        return view('agreement-group.create', ['selectedSale' => $sale, 'sales' => $sales]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAgreementGroupRequest $request)
    {
        $agreementGroup = AgreementGroup::create($request->all());
        if ($agreementGroup){
            return redirect()->route('sales.edit', $agreementGroup->sale)->with('success', 'ok');
        };
        return redirect()->back()->with('error', 'ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AgreementGroup  $agreementGroup
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(AgreementGroup $agreementGroup)
    {
        $sales = Sale::active()->get();
        return view('agreement-group.show', [
            'agreementGroup' => $agreementGroup,
            'sales' => $sales
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AgreementGroup  $agreementGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(AgreementGroup $agreementGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AgreementGroup  $agreementGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AgreementGroup $agreementGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AgreementGroup  $agreementGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(AgreementGroup $agreementGroup)
    {
        //
    }
}
