<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewTasksAlert extends Mailable
{
    use Queueable, SerializesModels;

    private $tasks;
    private $logo;
    private $projectLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tasks, $subject, $logo, $projectLink)
    {
        $this->tasks = $tasks;
        $this->subject($subject);
        $this->logo = $logo;
        $this->projectLink = $projectLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('vendor.mail.html.red-message', [
            'content' => view('invoice.new-tasks', [
                'tasks' => $this->tasks,
                'projectLink' => $this->projectLink
            ])->render(),
            'brandLogo' => $this->logo,
            'brandLink' => '#',
            'brand' => ''
        ]);
    }
}
