<?php

namespace App\Http\Controllers;

use App\Sale;
use App\Label;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SalesTableController extends Controller
{
    public function index(Request $request)
    {
        $result = Sale::query()->where('open', true)->orderBy('deadline', 'asc');

        $table = DataTables::of($result)->filter(function (Builder $query) {
            if (request()->has('searchBuilder') && !empty(request()->input('searchBuilder'))) {
                $searchCriterias = request()->input('searchBuilder');
                foreach ($searchCriterias['criteria'] as $criteria){
                    if (!isset($criteria['condition'])){
                        continue;
                    }
                    $valueOne = isset($criteria['value1']) ? $criteria['value1'] : null;
                    $valueTwo = isset($criteria['value2']) ? $criteria['value2'] : null;
                    switch ($criteria['condition']) {
                        case 'between':
                            $query->whereBetween($criteria['origData'], [$valueOne, $valueTwo]);
                            break;
                        case '!between':
                            $query->whereNotBetween($criteria['origData'], [$valueOne, $valueTwo]);
                            break;
                        case '>':
                            $query->where($criteria['origData'], '>', $valueOne);
                            break;
                        case '>=':
                            $query->where($criteria['origData'], '>=', $valueOne);
                            break;
                        case '<':
                            $query->where($criteria['origData'], '<', $valueOne);
                            break;
                        case '<=':
                            $query->where($criteria['origData'], '<=', $valueOne);
                            break;
                        case '=':
                            $query->where($criteria['origData'], '=', $valueOne);
                            break;
                        case '!=':
                            $query->where($criteria['origData'], '!=', $valueOne);
                            break;
                        case 'null':
                            $query->whereNull($criteria['origData']);
                            break;
                        case '!null':
                            $query->whereNotNull($criteria['origData']);
                            break;
                        case 'starts':
                            $query->where($criteria['origData'], 'like', $valueOne .'%');
                            break;
                        case '!starts':
                            $query->whereNot($criteria['origData'], 'like', $valueOne .'%');
                            break;
                        case 'ends':
                            $query->where($criteria['origData'], 'like','%'. $valueOne);
                            break;
                        case '!ends':
                            $query->whereNot($criteria['origData'], 'like','%'. $valueOne);
                            break;
                        case 'contains':
                            $query->where($criteria['origData'], 'like', '%'. $valueOne .'%');
                            break;
                        case '!contains':
                            $query->whereNot($criteria['origData'], 'like', '%'. $valueOne .'%');
                            break;
                    }
                }
            }
        });

        $table->addColumn('client', function (Sale $sale) {
            return $sale->client->name;
        });

        return $table->make(true);
    }
}
