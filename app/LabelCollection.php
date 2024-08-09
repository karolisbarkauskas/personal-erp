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

use Illuminate\Support\Collection;

class LabelCollection extends Collection
{
    public function containsPriorityLabels()
    {
        return
            $this->contains('label_id', Label::CRITICAL) ||
            $this->contains('label_id', Label::HIGH_PRIORITY) ||
            $this->contains('label_id', Label::NORMAL_PRIORITY) ||
            $this->contains('label_id', Label::LOW_PRIORITY);
    }
}
