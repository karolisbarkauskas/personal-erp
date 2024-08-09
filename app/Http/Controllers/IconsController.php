<?php

namespace App\Http\Controllers;

use App\Http\Requests\IconCreate;
use App\Icon;
use App\Logo;
use Illuminate\Http\Request;

class IconsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $icons = Icon::all();

        return view('icons.index', compact('icons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('icons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IconCreate $request)
    {
        $icon = Icon::create([
            'name' => $request->get('name')
        ]);

        $icon->addMedia($request->file('file'))->toMediaCollection('icons');

        return redirect()->route('icons.index')->with([
            'success' => 'OK, icon created'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Icon $icon)
    {
        $icon->clearMediaCollection();
        $icon->delete();

        return redirect()->route('icons.index')->with([
            'success' => 'OK, icon created'
        ]);
    }
}
