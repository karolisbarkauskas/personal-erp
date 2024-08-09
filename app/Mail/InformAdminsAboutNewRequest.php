<?php

namespace App\Mail;

use App\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformAdminsAboutNewRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Task
     */
    private $task;

    /**
     * Create a new message instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->subject('New PrestaPro inquiry');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.new-request', [
            'task' => $this->task
        ]);
    }
}
