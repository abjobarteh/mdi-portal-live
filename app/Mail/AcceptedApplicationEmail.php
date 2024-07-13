<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;

class AcceptedApplicationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $date;
    public $matnumber;

    public $fullname;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date, $matnumber, $fullname, $type)
    {
        $this->date = $date;
        $this->matnumber = $matnumber;
        $this->fullname = $fullname;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {



        if ($this->type == 1) {
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
        } else if ($this->type == 0) {
            $pdf = Pdf::loadView('emails.conditional_application_pdf', [
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
        // Generate the PDF

    }
}
