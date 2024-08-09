<?php

namespace App;

use App\Traits\TimeConvert;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class YoutrackRecord extends Model implements ToModel, WithCustomCsvSettings
{
    use TimeConvert;

    public $timestamps = false;

    protected $fillable = [
        'group_name',
        'item',
        'item_summary',
        'estimation',
        'sale',
        'spent_time',
    ];

    public function model(array $row)
    {
        if ($this->isInvalid($row)) {
            return;
        }

        return new YoutrackRecord([
            'group_name' => trim($row[0]),
            'item' => trim($row[1]),
            'item_summary' => $row[2],
            'estimation' => $row[3],
            'spent_time' => $row[4],
        ]);
    }

    /**
     * @param $row
     * @return bool
     */
    public function isInvalid($row): bool
    {
        return is_null($row[1]) || $row[1] == 'Item' || str_contains($row[1], 'BRC-');
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }
}
