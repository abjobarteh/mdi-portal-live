<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectedApplicationEmail extends Mailable
{
    use Queueable, SerializesModels;

    // public function build()
    // {
    //     return $this->subject('Application Status')
    //         ->view('emails.rejected_application');
    // }

    public function build()
    {
        // Generate the PDF
        $pdf = Pdf::loadView('emails.rejected_application_pdf');

        return $this->subject('Application Status')
            ->view('emails.rejected_application')
            ->attachData($pdf->output(), 'RejectionLetter.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
