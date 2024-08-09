<?php

namespace App\Http\Controllers;

use App\Url;
use Carbon\Carbon;

class UrlReportController extends Controller
{

    public function show(Url $url)
    {
        $result = [];
        $speeds = $url->googleSpeeds;

        $result['series'] = [
            [
            'axis'=> "speed",
            'valueField'=> "gDesktop",
            'name'=> "G desktop"
        ],[
            'axis'=> "speed",
            'valueField'=> "gMobile",
            'name'=> "G mobile"
        ],

        ];
        foreach ($speeds as $speed) {
            $result['data'][] =
                [
                    'gDesktop' => $speed->strategy ? NULL : $speed->score,
                    'date' => Carbon::parse($speed->created_at)->format('Y-m-d H:i:s'),
                    'gMobile' => $speed->strategy ? $speed->score : NULL,
                ];
        }
        return response()->json($result);
    }
}
