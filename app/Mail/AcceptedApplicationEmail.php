<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AcceptedApplicationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $date;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Application Status')
            ->view('emails.accepted_application')
            ->with([
                'date' => $this->date,
            ]);
    }
}
