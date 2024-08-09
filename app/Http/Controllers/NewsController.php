<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTvNews;
use App\Http\Requests\DeleteNew;
use App\Http\Requests\EditNew;
use App\Icon;
use App\TvNew;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $news = TvNew::orderBy('active', 'desc')->orderBy('active_from', 'desc')->orderBy('active_to', 'desc')->get();

        return view('news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        return view('news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateTvNews $request)
    {
        $new = TvNew::create(
            $request->only(['title']) + [
                'created_by' => auth()->id()
            ]
        );

        return redirect()->route('news.edit', $new)->with([
            'success' => 'OK, now you can add new parameters'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View|Response
     */
    public function edit(TvNew $news)
    {
        $icons = Icon::all();
        return view('news.edit', compact('news', 'icons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EditNew $request, TvNew $news)
    {
        $news->update($request->only([
                'heading',
                'content',
                'active',
                'icon_id',
                'side',
            ]) + [
                'active_from' => $request->date('active_from')->format('Y-m-d h:i'),
                'active_to' => $request->date('active_to')->format('Y-m-d h:i'),
                'active' => $request->get('active', false)
            ]);

        if ($request->hasFile('file')) {
            $news->clearMediaCollection('extra-content');
            $news->addMedia($request->file('file'))->toMediaCollection('extra-content');
        }

        return redirect()->route('news.edit', $news)->with([
            'success' => 'WELL DONE! New is now updated 🎉'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(DeleteNew $request, TvNew $news)
    {
        $news->delete();

        return redirect()->route('news.index')->with([
            'success' => 'WELL DONE! New is DELETED 🎉'
        ]);
    }
}
