<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Label;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ExpenseTableController extends Controller
{
    public function index(Request $request)
    {
        $result = Expense::with(['expenseCategory']);
        $table = DataTables::of($result)->filter(function (Builder $query) {
            $query->select(DB::raw('expenses.*'));
            $query->join('expenses_categories','expenses_categories.id','=', 'expenses.category');
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

            if (request()->has('order') && !empty(request()->input('order'))) {
                $orderBys = request()->input('order');
                $columns =request()->input('columns');
                foreach ($orderBys as $key => $orderBy) {
                    $columnName = $columns[$orderBy['column']]['name'];
                    $direction = $orderBy['dir'];
                    switch ($columnName) {
                        case 'expense_date':
                            $query->orderBy('expense_date', $direction);
                            break;
                        case 'issue_date':
                            $query->orderBy('issue_date', $direction);
                            break;
                        case 'name':
                            $query->orderBy('name', $direction);
                            break;
                        case 'dept':
                            $query->orderBy('dept', $direction);
                            break;
                        case 'category':
                            $query->orderBy('category', $direction);
                            break;
                        case 'size':
                            $query->orderBy('size', $direction);
                            break;
                        default:
                            $query->orderBy('expenses.id', 'desc');
                            break;
                    }
                }
            } else {
                $query->orderBy('expenses.id', 'desc');
            }
        });

        $table->addColumn('category', function (Expense $expense) {
            return $expense->expenseCategory->name;
        });
        $table->addColumn('sale', function (Expense $expense) {
            return $expense->sale? $expense->sale->name : '---';
        });
        $table->addColumn('formated_amount', function (Expense $expense) {
            return Label::formatPrice($expense->size);
        });
        $table->addColumn('size', function (Expense $expense) {
            return $expense->size;
        });
        $table->addColumn('uploaded', function (Expense $expense) {
            return $expense->hasMedia('file');
        });

        return $table->make(true);
    }
}
