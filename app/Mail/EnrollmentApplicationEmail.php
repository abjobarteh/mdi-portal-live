<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;

class EnrollmentApplicationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $date;
    public $matnumber;
     public $userid;
    public $fullname;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $fullname,$user_id)
    {
 
        $this->fullname = $fullname;
        $this->userid=$user_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $pdf = Pdf::loadView('emails.enrollment_application_pdf', [
            'fullname' => $this->fullname,
             'userid' => $this->userid
        ]);

        return $this->subject('Enrollment Status')
            ->view('emails.enrollment_application')
            ->attachData($pdf->output(), 'EnrollmentLetter.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
