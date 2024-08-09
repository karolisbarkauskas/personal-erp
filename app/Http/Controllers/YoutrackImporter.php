<?php

namespace App\Http\Controllers;

use App\YoutrackRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class YoutrackImporter extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('youtrack-importer.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('data')) {
            /** @var \Illuminate\Http\UploadedFile $file */
            foreach ($request->allFiles()['data'] as $file) {
                Excel::import(new YoutrackRecord(), $file);
            }
        }

        return redirect()->route('youtrack-importer.map')
            ->with('success', 'Uploaded');
    }

    public function map(Request $request)
    {
        $rows = YoutrackRecord::orderBy('id', 'desc')->get();
        if ($request->get('full')) {
            $rows = YoutrackRecord::whereNull('sale')->orderBy('id', 'desc')->get();
        }

        return view('youtrack-importer.map', get_defined_vars());
    }

}
