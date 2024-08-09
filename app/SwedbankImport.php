<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class SwedbankImport extends Model implements ToModel, WithCustomCsvSettings
{
    use SoftDeletes;

    protected $table = 'imports';

    protected $fillable = [
        'type',
        'number',
        'transaction_number',
        'date',
        'payer',
        'sum',
        'purpose',
        'credit_debit'
    ];

    public function model(array $row)
    {
        if ($this->inRedundantRow($row)) {
            return;
        }

        if (!Import::where('number', $row[8])->withTrashed()->first()) {
            return new Import([
                'type' => $row[3],
                'number' => $row[8],
                'transaction_number' => $row[8],
                'date' => Carbon::parse($row[2])->format('Y-m-d'),
                'payer' => $row[3],
                'sum' => (double)$row[5] * ($row[7] == 'D' ? -1 : 1),
                'purpose' => str_replace('/ / / / / / / /', '', $row[4]),
                'credit_debit' => $row[7]
            ]);
        }
    }

    private function inRedundantRow($row)
    {
        return $row[4] === 'Opening balance' ||
            is_null($row[8]) ||
            str_contains($row[4], 'Pervedimas tarp savo sąskaitų') !== false ||
            $row[7] == 'K';
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }
}
