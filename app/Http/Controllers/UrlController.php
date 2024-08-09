<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;

class UrlController extends Controller
{

    public function index()
    {
        $urls = Url::where('country_code','.lt')->orderBy('google_speed_check', 'desc')->paginate(50);

        return view('urls.index', compact('urls'));
    }

    public function edit(Url $url)
    {
        return view('urls.edit', compact('url'));
    }


}
