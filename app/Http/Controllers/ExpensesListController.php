<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpensesCategory;
use App\Http\Requests\ExpenseRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;

class ExpensesListController extends Controller
{
    public function index()
    {
        $report = Expense::whereNull('deleted_at')->orderBy('expense_date')->get()->groupBy(function ($val) {
            return Carbon::parse($val->expense_date)->format('Y-m');
        });
        $expenses = $this->formatReport($report);
        $categories = ExpensesCategory::all();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $report
     *
     * @return array
     */
    private function formatReport(\Illuminate\Database\Eloquent\Collection $report): array
    {
        $jan = Carbon::create(2023, 03, 01);
        $diff = $jan->diffInMonths(Carbon::now()) + 1;
        $expenses = [];

        for ($i = 0; $i <= $diff; $i++) {
            if ($report->has($jan->copy()->addMonths($i)->format('Y-m'))) {
                $expenses[] = [
                    'date' => $jan->copy()->addMonths($i)->format('Y-m'),
                    'report' => $report->get($jan->copy()->addMonths($i)->format('Y-m'))
                ];
            }
        }

        $expenses = array_reverse($expenses);
        return $expenses;
    }

    public function create()
    {
        $categories = ExpensesCategory::all();

        return view('expenses.create', get_defined_vars());
    }

    public function depts()
    {
        $report = Expense::depts()->get()->groupBy(function ($val) {
            return Carbon::parse($val->expense_date)->format('Y-m');
        })->reverse();
        $expenses = $this->formatReport($report);

        return view('expenses.index', compact('expenses'));
    }

    public function store(ExpenseRequest $request)
    {
        $expense = Expense::create($request->all());

        activity('expenses')
            ->performedOn($expense)
            ->withProperties($expense->getChanges())
            ->log('Created expense');

        return redirect()->route('expenses.index')
            ->with('success', 'New expense created');
    }

    public function edit(Expense $expense)
    {
        $categories = ExpensesCategory::all();
        $activity = Activity::inLog('expenses')->where('subject_id', $expense->id)->latest()->get();

        return view('expenses.edit', get_defined_vars());
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->all());

        activity('expenses')
            ->performedOn($expense)
            ->withProperties($expense->getChanges())
            ->log('Created expense');


        if ($request->hasFile('file')) {
            $expense->clearMediaCollection('file');
            $expense->addMedia($request->file('file'))->toMediaCollection('file');
        }

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense DELETED');
    }

    public function report()
    {
        $result = [];

        $expensesList = Expense::withTrashed()->orderBy('expense_date');

        if (request()->has('filters')) {
            $expensesList = $expensesList->whereIn('category', explode(',', request()->get('filters', '')));
        }
        $expensesList = $expensesList->get()->groupBy(function ($val) {
            return Carbon::parse($val->expense_date)->format('Y-m');
        });
        $result['series'] = [];

        foreach (ExpensesCategory::all() as $category) {
            $result['series'][] = [
                'valueField' => Str::slug($category->name, '_'),
                'name' => $category->name,
            ];
        }

        $result['data'] = [];

        foreach ($expensesList as $month => $expensesGroup) {
            $data = [
                'month' => $month
            ];
            /**
             * @var  $key
             * @var Expense $expense
             */
            foreach ($expensesGroup as $key => $expense) {
                if (!isset($data[Str::slug($expense->expenseCategory->name, '_')])) {
                    $data[Str::slug($expense->expenseCategory->name, '_')] = 0;
                }
                $data[Str::slug($expense->expenseCategory->name, '_')] += round($expense->size, 2);
            }
            foreach (ExpensesCategory::all() as $category) {
                if (!isset($data[Str::slug($category->name, '_')])) {
                    $data[Str::slug($category->name, '_')] = 0;
                }
            }
            $result['data'][] = $data;
        }

        return response()->json($result);
    }
}
