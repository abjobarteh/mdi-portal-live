<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdmissionCodeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $admissionCode;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admissionCode)
    {
        $this->admissionCode = $admissionCode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admission-code')
            ->with(['admissionCode' => $this->admissionCode])
            ->subject('Your Admission Code');
    }
}
