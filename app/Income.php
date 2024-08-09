<?php

namespace App;

use App\Mail\InformAboutNewIncome;
use App\Scopes\InvoiceScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

/**
 * @property \App\Sale sale
 */
class Income extends Model
{
    use SoftDeletes;

    const NONE = 0;
    const DEBT = 4;
    const DISPLAY_TYPE_FULL = 1;
    const PLANNED = 1;
    const ONESOFT = 1;
    const PRESTAPRO = 2;
    const SENT = 2;
    const CREDITED = 5;
    public const INVOICE_PREFIX = 'INVR-';
    public const CREDIT_INVOICE_PREFIX = 'CR-INVR-';
    public const PROFORMA_PREFIX = 'PI-INVR-';
    const PERCENTAGE = 0;
    const AMOUNT = 1;
    public const INVOICE = 1;
    public const CREDIT_INVOICE = 2;
    public const PROFORMA = 3;
    protected $connection = 'mysql';
    protected $fillable = [
        'invoice_type',
        'client',
        'sale_id',
        'category',
        'status',
        'issue_date',
        'income_date',
        'invoice_no',
        'description',
        'amount',
        'vat_size',
        'vat_amount',
        'total',
        'discount',
        'send_date',
        'type',
        'display_style',
        'short_service',
        'discount_reason',
        'company_id',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new InvoiceScope);
    }

    protected $requiredAttributes = [
        'invoice_no',
        'send_date',
    ];

    protected $casts = [
        'send_date' => 'datetime'
    ];

    public static function getInvoiceNumber($prefix, $type = self::INVOICE)
    {
        $invoice = Income::where('invoice_type', $type)
            ->whereNotNull('invoice_no')
            ->orderBy('issue_date', 'DESC')
            ->orderBy('invoice_no', 'DESC')
            ->limit(1)
            ->first();

        if (!$invoice) {
            return $prefix . sprintf("%02d", 1);
        }
        $number = (int)preg_replace(
            "/[^0-9]/",
            "",
            str_replace($prefix, '', $invoice->invoice_no)
        );
        $number++;

        return $prefix . sprintf("%02d", $number);
    }

    public static function compareMonthPerformance($month1, $month2, $lastYear = false)
    {
        return (self::getInvoiceSumByMonth($month1) - self::getInvoiceSumByMonth($month2, $lastYear));
    }

    public static function getInvoiceSumByMonth(int $month, $lastYear = false)
    {
        $now = Carbon::now();
        $date = $lastYear ? $now->subYear() : $now;
        $date = $date->setMonth($month);
        return Income::whereBetween('income_date', [
            $date->copy()->startOfMonth(),
            $date->copy()->endOfMonth()
        ])
            ->where('status', '!=', self::PLANNED)
            ->sum('amount');
    }

    public static function getMarginForMonthCompare(int $month, $all = true, $year = false)
    {
        return self::getMarginForMonthDiff($month, $all, $year) > 0 ? 'text-success' : 'text-danger';
    }

    public static function getMarginForMonthDiff(int $month, $all = true, $year = false)
    {
        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        return self::getMarginForMonth($month, $all, $year) - self::getMarginForMonth($month, $all, $year - 1);
    }

    public static function getMarginForMonth(int $month, $all = true, $year = false)
    {
        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        return Income::getProfitMargin(
            self::getIncomeForMonth($month, $year),
            Expense::getExpenseAverageForMonth($month, $all, $year)
        );
    }

    /**
     * How to calculate profit margin
     * Find out your COGS (cost of goods sold). For example $30.
     * Find out your revenue (how much you sell these goods for, for example $50).
     * Calculate the gross profit by subtracting the cost from the revenue. $50 - $30 = $20
     * Divide gross profit by revenue: $20 / $50 = 0.4.
     * Express it as percentages: 0.4 * 100 = 40%.
     * This is how you calculate profit margin... or simply use our gross margin calculator!
     * @param $income
     * @param $expenses
     * @return float
     */
    public static function getProfitMargin($income, $expenses): float
    {
        if (!$income) {
            return 0;
        }
        return round((($income - $expenses) / $income) * 100, 2);
    }

    public static function getIncomeForMonth(int $month, $year = false)
    {

        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        $now = Carbon::createFromFormat('Y m', $year . ' ' . $month);

        return Income::whereBetween('income_date', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->where('status', '!=', self::PLANNED)
            ->whereNotNull('invoice_no')
            ->sum('amount');
    }

    public static function getMarginForYearCompare(int $year, $all = true)
    {
        return self::getYearlyMargin($year) - self::getYearlyMargin($year - 1) > 0 ? 'text-success' : 'text-danger';
    }

    public static function getYearlyMargin(int $year, $all = true)
    {
        return Income::getProfitMargin(self::getYearlyIncome($year), Expense::getYearlyExpense($year, $all));
    }

    public static function getYearlyIncome($year)
    {
        $now = Carbon::createFromFormat('Y', $year);
        return Income::whereBetween('income_date', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])
            ->where('status', '!=', self::PLANNED)
            ->whereNotNull('invoice_no')
            ->sum('amount');
    }

    public static function getTypeQuantity(int $type, $sum)
    {
        if (($sum > 0 && $type == self::CREDIT_INVOICE) || ($sum < 0 && $type != self::CREDIT_INVOICE)) {
            return -1;
        }

        return 1;
    }

    public function getOutstandingDays()
    {
        return now()->diffInDays(Carbon::createFromFormat('Y-m-d', $this->issue_date));
    }

    public function getServiceValue(Service $service)
    {
        return $this->serviceValues()->where('service_id', $service->id)->first();
    }

    public function serviceValues()
    {
        return $this->hasMany(IncomeServiceValues::class);
    }

    public function reports()
    {
        return $this->hasMany(IncomeReport::class);
    }

    public function getStatusClass()
    {
        switch ($this->status) {
            case Status::SENT:
                return 'warning';
            case Status::PLANNED:
                return 'info';
            case Status::PAID:
                return 'success';
            case Status::DEPT:
                return 'danger';
            case Status::CREDITED:
                return 'secondary';
        }
    }

    public function incomeCategory()
    {
        return $this->hasOne(IncomeCategory::class, 'id', 'category');
    }

    public function incomeClient()
    {
        return $this->hasOne(Client::class, 'id', 'client');
    }

    public function incomeStatus()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }
    public function currentMonthPayments()
    {
        return $this->hasMany(Payment::class, 'income_id', 'id')
            ->whereBetween('created_at', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]);
    }

    public function getIncomeMonth()
    {
        return Carbon::createFromFormat('Y-m-d', $this->issue_date)->format('Y-m') . ' mėn.';
    }

    public function scopeDepts(Builder $builder)
    {
        return $builder->where('status', Income::DEBT);
    }

    public function isDept(): bool

    {
        return $this->status == Income::DEBT;
    }

    public function scopeHasInvoice(Builder $builder)
    {
        return $builder->where('status', '!=', Income::NONE);
    }

    public function scopePlans(Builder $builder)
    {
        return $builder->where('status', Income::PLANNED);
    }

    public function scopeActiveInvoices(Builder $builder)
    {
        return $builder->whereIn('status', [
            Income::PLANNED,
            Income::SENT,
            Income::DEBT,
        ]);
    }

    public function isPaid()
    {
        return $this->payment()->sum('amount') >= $this->total;
    }

    public function unpaid()
    {
        return $this->payment()->sum('amount') - $this->total;
    }

    public function payment()
    {
        return $this->hasMany(Payment::class, 'income_id', 'id');
    }

    public function hasDept()
    {
        return !$this->isOverPaid();
    }

    public function getDept()
    {
        return $this->payment()->sum('amount') - $this->total;
    }

    public function markAsPaid()
    {
        $this->update([
            'status' => Status::PAID
        ]);
    }

    public function isDataReady()
    {
        foreach ($this->requiredAttributes as $requiredAttribute) {
            if (!$this->attributes[$requiredAttribute]) {
                return false;
            }
        }

        return true;
    }

    public function getPaymentOverdueDays()
    {
        return Carbon::createFromFormat('Y-m-d', $this->issue_date)
            ->addDays($this->incomeClient->payment_delay)->diff(now())->days;
    }

    public function getUnpaidMoney()
    {
        return $this->total - $this->paymentTotals();
    }

    public function paymentTotals()
    {
        return $this->payment()->sum('amount');
    }

    public function emails()
    {
        return $this->hasMany(IncomeEmails::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function generateReport($language)
    {
        app()->setLocale($language);

        $customer = new Party([
            'name' => $this->incomeClient->name
        ]);

        $seller = new Party([
            'name' => $this->company->name,
            'address' => $this->company->address,
            'code' => $this->company->code,
            'vat' => $this->company->vat_code,
            'custom_fields' => [
                'IBAN' => $this->company->iban,
                'BANK' => $this->company->bank,
                'SWIFT' => $this->company->swift
            ]
        ]);

        $items = [];

        foreach ($this->reports()->where('include', true)->get() as $value) {
            $name = "";

            if ($value->task_id) {
                $name .= "<strong>{$value->task_id}</strong> <br>";
            }
            $name .= $value->name;
            if ($value->task_link) {
                $name .= "<br /> {$value->task_link}";
            }

            $items[] = (new InvoiceItem())
                ->title($name)
                ->pricePerUnit($value->hourly_rate)
                ->quantity($value->hours);
        }

        $notes = [
            '<strong>',
            __('invoices.what-this-is'),
            '</strong>'
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make(
            __('invoices.report')
        )
            ->buyer($customer)
            ->seller($seller)
            ->date(Carbon::createFromFormat('Y-m-d', $this->issue_date))
            ->dateFormat('Y-m-d')
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->currencyFormat('{VALUE} {SYMBOL}')
            ->currencyThousandsSeparator('')
            ->currencyDecimalPoint('.')
            ->addItems($items)
            ->notes($notes)
            ->template('report')
            ->logo($this->getLogo($this));

        if ($this->hasDiscount()) {
            $invoice->totalDiscount($this->discount, $this->discountType());
        }

        return $invoice;
    }

    public function generateInvoice($language = 'lt'): Invoice
    {
        app()->setLocale($language);

        $customer = new Party([
            'name' => $this->incomeClient->name,
            'address' => $this->incomeClient->address,
            'code' => $this->incomeClient->code,
            'vat' => $this->incomeClient->vat_code,
            'custom_fields' => [

            ],
        ]);

        $seller = new Party([
            'name' => $this->company->name,
            'address' => $this->company->address,
            'code' => $this->company->code,
            'vat' => $this->company->vat_code,
            'custom_fields' => [
                'IBAN' => $this->company->iban,
                'BANK' => $this->company->bank,
                'SWIFT' => $this->company->swift
            ]
        ]);

        $items = [];
        if ($this->isFullDisplay()) {
            /** @var IncomeServiceValues $value */
            foreach ($this->serviceValues as $value) {
                $items[] = (new InvoiceItem())
                    ->title($value->service->name)
                    ->pricePerUnit($value->amount)
                    ->quantity(1);
            }
        } else {
            $items[] = (new InvoiceItem())
                ->title($this->short_service)
                ->pricePerUnit($this->serviceValues->sum('amount'))
                ->quantity(1);
        }

        $notes = [
            __('invoices.note'),
            '<strong>',
            __('invoices.payment-instructions'),
            '</strong>'
        ];
        $notes = implode("<br>", $notes);

        $parts = explode('-', $this->invoice_no);
        $sequence = end($parts);

        $prefix = substr($this->invoice_no, 0, strrpos($this->invoice_no, '-')+1);

        $delay = Carbon::createFromFormat('Y-m-d', $this->issue_date)->diffInDays($this->send_date);
        $invoice = Invoice::make($this->getInvoiceTitle())
            ->series($prefix)
            ->sequence($sequence)
            ->serialNumberFormat('{SERIES}{SEQUENCE}')
            ->buyer($customer)
            ->seller($seller)
            ->date(Carbon::createFromFormat('Y-m-d', $this->issue_date))
            ->dateFormat('Y-m-d')
            ->payUntilDays($this->incomeClient->payment_delay + $delay)
            ->currencySymbol('€')
            ->currencyCode('EUR')
            ->taxRate($this->vat_size)
            ->currencyFormat('{VALUE} {SYMBOL}')
            ->currencyThousandsSeparator('')
            ->currencyDecimalPoint('.')
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('images/empty.png'));

        if ($this->company->show_logo) {
            $invoice
                ->logo($this->getLogo($this));
        }

        if ($this->hasDiscount()) {
            $invoice->totalDiscount($this->discount, $this->discountType());
        }

        return $invoice;
    }

    public function isFullDisplay()
    {
        return $this->display_style == Income::DISPLAY_TYPE_FULL;
    }

    public function getInvoiceTitle()
    {
        switch ($this->invoice_type) {
            case self::INVOICE:
                return $this->company->vat_code ? __('invoices.title') : __('invoices.non_title');
            case self::CREDIT_INVOICE:
                return __('invoices.credit-title');
            case self::PROFORMA:
                return __('invoices.proforma-title');
        }

        return __('invoices.title');
    }

    /**
     * @param Income $income
     * @return string
     */
    private function getLogo(Income $income): string
    {
        return public_path('images/logo.png');
    }

    public function hasDiscount()
    {
        return $this->discount > 0;
    }

    public function discountType()
    {
        return $this->type === 0;
    }

    public function isIncomeNotFinished(): bool
    {
        return $this->status > 0;
    }

    /**
     * @return void
     */
    public function updateTotalProgrammingServices(): void
    {
        $total = $this->reports()->where('include', true)->sum('total');

        $this->serviceValues()->updateOrCreate([
            'income_id' => $this->id
        ], [
            'service_id' => Service::SOFTWARE_DEVELOPMENT,
            'amount' => $total
        ]);

        $this->update([
            'amount' => $this->serviceValues->sum('amount')
        ]);
    }

    public function reSaveInvoiceByService()
    {
        $this->serviceValues()->firstOrCreate([
            'amount' => $this->amount,
            'service_id' => $this->getServiceId()
        ]);
    }

    public function getServiceId()
    {
        $services = [
            self::ONESOFT => 3,
            self::PRESTAPRO => 2
        ];

        return $services[$this->incomeClient->category];
    }

    public function determineType()
    {
        if (strpos($this->invoice_no, self::CREDIT_INVOICE_PREFIX) !== false) {
            return self::CREDIT_INVOICE;
        }

        if (strpos($this->invoice_no, self::PROFORMA_PREFIX) !== false) {
            return self::PROFORMA;
        }

        return self::INVOICE;
    }

    public function setAmount($amount)
    {
        if ($amount > 0 && $this->isCreditInvoice()) {
            return $amount * -1;
        }

        if ($amount < 0 && !$this->isCreditInvoice()) {
            return $amount * -1;
        }

        return $amount;
    }

    /**
     * @return bool
     */
    public function isCreditInvoice(): bool
    {
        return $this->invoice_type == self::CREDIT_INVOICE;
    }

    public function getOverDueTime()
    {
        return $this->getPaymentDeadline();
    }

    public function getPaymentDeadline()
    {
        if ($this->send_date) {
            return $this->send_date->addDays($this->incomeClient->payment_delay)->isPast();
        }

        return Carbon::createFromFormat('Y-m-d', $this->issue_date)
            ->addDays($this->incomeClient->payment_delay)
            ->isPast();
    }

    public function cashFlow()
    {
        return $this->hasOne(CashFlow::class);
    }

    public function assignCashFlow()
    {
        $incomeDate = now();

        return $this->cashFlow()->create([
            'income_date' => $incomeDate->year . '-' . $incomeDate->month . '-01',
            'type' => CashFlow::INCOME,
            'flow_name' => $this->incomeClient->name . ' ' . $this->invoice_no,
            'initial' => $this->total,
            'real' => $this->total,
            'paid' => $this->status == Status::PAID
        ]);
    }

    public function getEmailContent()
    {
        if ($this->serviceValues->pluck('service_id')->count() == 1 &&
            $this->serviceValues->pluck('service_id')->first() == 2) {
            return "
            Sveiki, <br />
            Siunčiame sąskaitą už serverio nuomą. <br />
            <br />
            <br />
            Gražios dienos!";
        }

        return "
            Sveiki, <br />
            Siunčiame sąskaitą už darbus. <br />
            Darbų ataskaita prisegta prie šio laiško.
            <br />
            <br />
            Gražios dienos!";
    }

    public function sendViaEmail(IncomeEmails $email, $language)
    {
        $this->generateInvoice($language)
            ->filename($this->invoice_no)
            ->save('invoices');

        if ($this->reports->isNotEmpty()) {
            $this->generateReport($language)
                ->filename('report')
                ->save('reports');
        }

        Mail::send(new \App\Mail\Invoice($email, $language));
    }

    public function syncToReport(WeekTask $weekTask, $delete)
    {
        if (!$weekTask->isCompleted() && !$weekTask->isInvoiced()) {
            return;
        }

        $totalSoldTaskHours = $weekTask->task->weeklyTasks()
            ->whereIn('status', [WeekTask::INVOICED, WeekTask::COMPLETED])
            ->sum('sold_to_client');

        if ($delete) {
            $totalSoldTaskHours = $weekTask->task->weeklyTasks()
                ->whereIn('status', [WeekTask::INVOICED, WeekTask::COMPLETED])
                ->where('id', '!=', $weekTask->id)
                ->sum('sold_to_client');
        }

        preg_match('/^([^-\s]+-[^-\s]+)/', $weekTask->name, $matches);
        $taskId = $matches[0] ?? null;
        $taskName = ltrim(str_replace($taskId, '', $weekTask->name));

        $alreadySoldTaskHours = IncomeReport::where('task_id', $taskId)
            ->where('income_id', '!=', $this->id)
            ->sum('hours');

        $remarks = [];
        $remarks[] = $weekTask->remarks;
        if ($alreadySoldTaskHours > 0) {
            $remarks[] = "This task is already billed!";
        }

        $hours = $totalSoldTaskHours - $alreadySoldTaskHours;
        $this->reports()->updateOrCreate([
            'task_id' => $weekTask->task->code,
        ], [
            'name' => $taskName . implode("\n", $remarks),
            'hours' => $hours,
            'hourly_rate' => $this->incomeClient->rate,
            'total' => $hours * $this->incomeClient->rate,
            'done' => $weekTask->isInvoiced(),
        ]);

        if (auth()->user()->id != 1) {
            Mail::send(new InformAboutNewIncome($this));
        }

    }

}
