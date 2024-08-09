<?php

namespace App\Http\Controllers;

use App\ExpensesCategory;
use App\Import;
use App\Income;
use App\RecurringExpense;
use App\Sale;
use App\SwedbankImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $import = Import::orderBy('sum', 'desc')->get();
        $categories = ExpensesCategory::all();
        $recurringExpenses = RecurringExpense::all();
        $incomes = Income::all();

        return view('import.create', get_defined_vars());
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Exception
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($request->has('files')) {
            /** @var \Illuminate\Http\UploadedFile $file */
            foreach ($request->allFiles()['files'] as $file) {
                Excel::import(new Import(), $file);
            }
        }

        if ($request->has('swedbank')) {
            /** @var \Illuminate\Http\UploadedFile $file */
            foreach ($request->allFiles()['swedbank'] as $file) {
                Excel::import(new SwedbankImport(), $file);
            }
        }

        if ($request->get('import')) {
            $ignore = $request->get('ignore', []);

            foreach ($request->get('id') as $import) {
                /** @var Import $imp */
                $imp = Import::find($import);
                if ($imp) {
                    if (isset($ignore[$import])) {
                        $imp->delete();
                        continue;
                    }
                    $sale = $request->get('sale_id', [])[$import] ?? false;
                    $vat = $request->get('vat', [])[$import] ?? false;
                    $recurringExpense = $request->get('recurring_expense', [])[$import] ?? false;
                    if ($recurringExpense) {
                        $expenseCategory = RecurringExpense::find($recurringExpense)->category;
                    } else {
                        $expenseCategory = $request->get('expense_category', [])[$import] ?? false;
                    }
                    $income = $request->get('income_id', [])[$import] ?? false;

                    if ($imp->process($sale, $vat, $expenseCategory, $income, 0, $recurringExpense)) {
                        $imp->delete();
                    }
                }
            }
            return redirect()->route('import.create')->with('success', 'Information updated');
        }

        return redirect()->route('import.create')->with('success', 'Files uploaded');
    }
}
