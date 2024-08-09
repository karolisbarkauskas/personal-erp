<?php

use Illuminate\Database\Seeder;

class IncomeStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Status::query()->truncate();

        \App\Status::create([
            'name' => 'Planned'
        ]);

        \App\Status::create([
            'name' => 'Sent'
        ]);

        \App\Status::create([
            'name' => 'Paid'
        ]);
    }
}
