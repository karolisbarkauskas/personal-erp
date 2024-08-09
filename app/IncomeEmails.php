<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Income income
 */
class IncomeEmails extends Model
{
    protected $fillable = [
        'income_id',
        'user_id',
        'subject',
        'receivers',
        'attach_debts',
        'attach_report',
        'content',
        'send_at',
        'locale',
    ];

    public function getReceivers()
    {
        return json_decode($this->receivers);
    }

    public function income()
    {
        return $this->hasOne(Income::class, 'id', 'income_id');
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getBrandName()
    {
        return 'invoyer';
    }

    public function getBrandDomain()
    {
        return 'https://invoyer.com';
    }

    public function getBrandLink()
    {
        return 'https://invoyer.com';
    }

    public function getbrandLogo()
    {
        return 'https://invoicing.invoyer.dev/images/logo.svg';
    }

}
