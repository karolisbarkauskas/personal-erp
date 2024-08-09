<?php

namespace App\Http\Controllers;

use App\ClientContacts;
use Illuminate\Http\Request;

class ClientContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ClientContacts::create($request->only([
            'full_name',
            'phone',
            'email',
            'client_id'
        ]));

        return redirect()->back()->with('success', 'OK');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientContacts $client_contact)
    {
        $client_contact->delete();

        return redirect()->back()->with('success', 'OK');
    }
}
