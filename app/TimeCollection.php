<?php
/**
 * @copyright C MB "Parduodantys sprendimai" 2019
 *
 * This Software is the property of Parduodantys sprendimai, MB
 * and is protected by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact MB "Parduodantys sprendimai":
 * E-mail: info@onesoft.io
 * http://www.onesoft.io
 *
 */

namespace App;

use App\Traits\TimeConvert;
use Illuminate\Support\Collection;

class TimeCollection extends Collection
{
    use TimeConvert;

    public function getNotBillableTime()
    {
        return $this->where('billable_status', false)->sum('value');
    }

    public function getBookedTime()
    {
        return $this->sum('value');
    }

    public function getTimeDifference()
    {
        return abs(($this->getExpectedWorkTime()) - $this->getBillableTime());
    }

    public function getExpectedWorkTime()
    {
        return $this->first()->employee->expected_work_time;
    }

    public function getBillableTime()
    {
        return $this->where('billable_status', true)->sum('value');
    }

    public function getExpectedWorkTimeWithDaysOff($days, $daysOff)
    {
        return $this->first()->employee->expected_work_time * $days - ($daysOff * $this->first()->employee->expected_work_time);
    }

    public function getEmployeeDailyWorkHours()
    {
        return $this->first()->employee->expected_work_time;
    }
}
