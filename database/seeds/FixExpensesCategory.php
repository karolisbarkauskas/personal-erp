<?php

use App\BankAccount;
use App\Expense;
use Illuminate\Database\Seeder;

class FixExpensesCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $expenses = Expense::all();
        foreach ($expenses as $expens){
            $ibanPattern = '/[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}/';
            $iban = null;
            $result = preg_match($ibanPattern, $expens->name, $iban);
            if ($result) {
                $bankAccount = BankAccount::where('iban', $iban[0])->first();
                if ($bankAccount) {
                    if ($expens->category != $bankAccount->exp_cat_id) {
                        $expens->category =  $bankAccount->exp_cat_id;
                        $expens->save();
                    }
                }
            }
        }
    }
}
