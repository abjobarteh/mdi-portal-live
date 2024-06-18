<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RevertApplicationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $revertMessage;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($revertMessage)
    {
        $this->revertMessage = $revertMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.revert')
            ->with(['msg' => $this->revertMessage])
            ->subject('Your Application Is Reverted');
    }
}
