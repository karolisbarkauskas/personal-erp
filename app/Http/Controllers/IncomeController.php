<?php

namespace App\Http\Controllers;

use App\Client;
use App\Company;
use App\Http\Requests\InvoiceRequest;
use App\Income;
use App\Sale;
use App\Service;
use App\Settings;
use App\Status;
use App\Vat;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomes = Income::orderBy('income_date')->get()->groupBy(function ($val) {
            return Carbon::parse($val->income_date)->format('Y-m');
        })->reverse();

        $companies = Company::all();

        return view('income.index', compact('incomes', 'companies'));
    }

    public function change(Company $company)
    {
        auth()->user()->update([
            'current_company' => $company->id
        ]);

        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     */
    public function plans()
    {
        $companies = Company::all();

        return view('income.plans', compact('companies'));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $statuses = Status::all();
        $clients = Client::all();
        $short = request()->has('short');

        return view('income.create', get_defined_vars());
    }

    /**
     * @param \App\Http\Requests\InvoiceRequest $request
     */
    public function store(InvoiceRequest $request)
    {
        /** @var Income $income */
        $income = Income::create(
            $request->only('client') + [
                'company_id' => auth()->user()->current_company
            ]
        );
        $income->assignCashFlow();

        activity('income')
            ->performedOn($income)
            ->withProperties($income->getChanges())
            ->log('Created new income');

        return redirect()
            ->route('income.edit', $income->id)
            ->with('success', 'Created!');
    }

    /**
     * @param \App\Income $income
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Income $income)
    {
        $statuses = Status::all();
        $clients = Client::all();
        $activity = Activity::inLog('income')->where('subject_id', $income->id)->latest()->get();
        $services = Service::all();
        $companies = Company::all();

        $nextInvoiceProforma = Income::getInvoiceNumber(auth()->user()->company->proforma, Income::PROFORMA);
        $nextInvoice = Income::getInvoiceNumber(auth()->user()->company->invoice);
        $nextInvoiceCredit = Income::getInvoiceNumber(auth()->user()->company->credit, Income::CREDIT_INVOICE);

        return view(
            'income.edit',
            get_defined_vars()
        );
    }

    /**
     * @param \App\Http\Requests\InvoiceRequest $request
     * @param \App\Income $income
     */
    public function update(InvoiceRequest $request, Income $income)
    {
        if (!$income->cashFlow && !$income->isPaid()) {
            $income->assignCashFlow();
        }

        $income->update([
            'company_id' => $request->get('company_id')
        ]);

        auth()->user()->update([
            'current_company' => $request->get('company_id')
        ]);

        if ($request->get('status') == 0) {

            $income->update([
                'status' => 0,
                'description' => $request->get('description')
            ]);

            return redirect()
                ->back()
                ->with('success', 'STATUS SET');
        }

        $income->update($request->all());

        $type = $income->fresh()->determineType();

        if ($request->has('service')) {
            $income->serviceValues->each->delete();
            foreach ($request->get('service', []) as $service => $item) {
                if ($item) {
                    $income->serviceValues()->create([
                        'amount' => $item * Income::getTypeQuantity($type, $item),
                        'service_id' => $service
                    ]);
                }
            }
        }

        $sum = array_sum($request->get('service', []));
        $income->update([
            'amount' => $sum * Income::getTypeQuantity($type, $sum),
            'invoice_type' => $type
        ]);

        Settings::calculatePayableVat();

        activity('income')
            ->performedOn($income)
            ->withProperties($income->getChanges())
            ->log('Updated income information');

        return redirect()
            ->back()
            ->with('success', 'Income information updated');
    }

    /**
     * @param \App\Income $income
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Income $income)
    {
        $income->delete();

        return response()->json([
            'message' => 'Income deleted',
        ]);
    }

    public function incomeFromSale(Sale $sale)
    {
        $client = $sale->client();
        $data = $sale->getDataForNewInvoice();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $income = Income::create([
            'client' => $client->id,
            'sale_id' => $sale->id,
            'category' => $client->category,
            'status' => 1,
            'issue_date' => $endOfLastMonth,
            'income_date' => $endOfLastMonth,
            'send_date' => Carbon::today(),
            'invoice_no' => null,
            'description' => implode("", $data['description']),
            'amount' => $data['amount'],
            'vat_size' => $client->vat,
            'vat_amount' => $data['amount'] * $client->vat / 100,
            'total' => $data['amount'] * (1 + $client->vat / 100),
        ]);

        return route('income.edit', $income);
    }
}
