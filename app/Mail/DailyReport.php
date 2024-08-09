<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyReport extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Reports\Productivity */
    private $report;
    /**
     * @var string
     */
    private $date;

    /**
     * Create a new message instance.
     *
     * @param $report
     */
    public function __construct($report)
    {
        $this->report = $report;
        $this->date = Carbon::yesterday()->format('Y-m-d');
        $this->subject($this->date . ' time report');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.today', [
            'day' => $this->date,
            'timeToday' => $this->report->getTimeBookedYesterday(),
            'offDaysInMonth' => $this->report->getDaysOffWithinMonth(),
        ]);
    }
}
