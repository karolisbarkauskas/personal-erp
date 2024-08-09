<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogoUpload;
use App\Logo;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class LogosController extends Controller
{
    public function index()
    {
        $logos = Logo::all();

        return view('logos.index', compact('logos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('logos.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function store(LogoUpload $request)
    {
        if ($request->has('files')) {
            /** @var UploadedFile $file */
            foreach ($request->file('files') as $file) {
                /** @var Logo $logo */
                $logo = Logo::create();

                $logo->addMedia($file)->toMediaCollection('brands');
            }
        }

        return redirect()->route('logos.index');
    }

    public function destroy(Logo $logo)
    {
        $logo->clearMediaCollection();
        $logo->delete();

        return redirect()->route('logos.index');
    }
}
