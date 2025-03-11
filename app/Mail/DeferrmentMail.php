<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DeferrmentMail extends Mailable
{
    use Queueable, SerializesModels;


    public int $id;
    public string $fullname;

     public string $mat_number ;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $fullname, $mat_number)
    {
        $this->id = $id;
        $this->fullname = $fullname;
        $this->mat_number = $mat_number;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Deferement Acceptance Letter',
        );
    }

    public function build (){

        $pdf = Pdf::loadView('emails.deferrment_pdf', [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'matnumber' => $this->mat_number,
        ]);

        return $this->subject('Student Status And New Acceptance Letter')
        ->view('emails.deferrment')
        ->attachData($pdf->output(), 'AcceptanceLetter.pdf', [
            'mime' => 'application/pdf',
        ]);
    }
    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
 /*   public function content()
    {
        return new Content(
            view: 'emails.deferrment',
           /* with: [
                'id' => $this->id,
                'fullname' => $this->fullname,
                'matnumber' => $this->mat_number,
            ] 
        );
    } */

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
 /*   public function attachments()
    {
        // Add attachments dynamically if needed
        return [];
    }  */
}
