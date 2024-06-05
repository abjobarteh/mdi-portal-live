<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class AcceptedApplicationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $date;
    public $matnumber;

    public $fullname;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date, $matnumber, $fullname)
    {
        $this->date = $date;
        $this->matnumber = $matnumber;
        $this->fullname = $fullname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generate the PDF
        $pdf = Pdf::loadView('emails.accepted_application_pdf', [
            'date' => $this->date,
            'matnumber' => $this->matnumber,
            'fullname' => $this->fullname,
        ]);

        return $this->subject('Application Status')
            ->view('emails.accepted_application')
            ->attachData($pdf->output(), 'AcceptanceLetter.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
