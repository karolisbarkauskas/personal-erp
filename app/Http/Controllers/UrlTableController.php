<?php

namespace App\Http\Controllers;

use App\Url;

class UrlTableController extends Controller
{
    public function index()
    {
        $urls = Url::all();
        $result = [];
        $result['urls'] = [];
        foreach ($urls as $url) {
            $data = [];
            $data['id'] = $url->id;
            $data['parent'] = 0;
            $data['url'] = $url->url;
            $data['ssl'] = $url->ssl;
            $data['php'] = $url->php;
            $data['country_code'] = $url->country_code;
            $data['sitemap_check'] = $url->sitemap_check;
            $data['sitemap_status'] = $url->sitemap_status;
            $data['google_speed_count'] = 0;
            $data['google_speed_check'] = $url->google_speed_check;
            $data['google_speed_status'] = $url->google_speed_status;
            $data['gtmetrix_check'] = $url->gtmetrix_check;
            $data['gtmetrix_status'] = $url->gtmetrix_status;
            $result['urls'][] = $data;
        }
        $result['expandedRowKeys'] = [];
        $result['selectedRowKeys'] = [];
        return response()->json($result);
    }
}
