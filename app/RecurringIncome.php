<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringIncome extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'service_id',
        'expense_id',
        'company_id',
        'date',
        'amount',
        'vat_size',
        'service_line',
        'period',
        'next_invoice_date',
    ];

    public function getAverageAttribute()
    {
        return round($this->amount / $this->period, 2);
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function category()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }

    public function generateInvoice()
    {
        /** @var Income $invoice */
        $invoice = Income::create([
            'client' => $this->client_id,
            'status' => Income::PLANNED,
            'issue_date' => now()->format('Y-m-d'),
            'income_date' => now()->format('Y-m-d'),
            'send_date' => now()->format('Y-m-d'),
            'description' => $this->category->name,
            'amount' => $this->amount,
            'vat_size' => $this->vat_size,
            'company_id' => $this->company_id,
            'invoice_no' => Income::getInvoiceNumber($this->company->invoice),
            'short_service' => $this->service_line . " " . now()->addMonths($this->period)->format('Y-m-d'),
        ]);

        $invoice->serviceValues()->create([
            'amount' => $this->amount,
            'service_id' => $this->service_id
        ]);
        $this->update([
            'next_invoice_date' => now()->addMonths($this->period)
        ]);
    }

}
