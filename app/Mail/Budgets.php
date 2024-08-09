<?php

namespace App\Mail;

use App\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Budgets extends Mailable
{
    use Queueable, SerializesModels;

    private $changes;

    /**
     * Create a new message instance.
     *
     * @param $changes
     */
    public function __construct($changes)
    {
        $this->changes = $changes;
        $this->subject('Budgets changes for current projects');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.budgets', [
            'sales' => $this->formatSales()
        ]);
    }

    private function formatSales()
    {
        $result = [];
        foreach ($this->changes as $sale) {
            $saleModel = Sale::find($sale['sale_id']);
            $result[] = [
                'sale' => $saleModel,
                'from' => $sale['from'],
                'to' => $sale['to'],
                'diff' => $sale['diff']
            ];
        }
        return $result;
    }
}
