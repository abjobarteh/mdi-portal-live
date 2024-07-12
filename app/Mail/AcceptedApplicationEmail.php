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

        $studentsWithPrograms = Student::join('programs as b', 'students.program_id', '=', 'b.id')
            ->where('students.mat_number', $this->matnumber)
            ->select('b.fee', 'b.name')
            ->get();

        // Initialize variables to hold program details
        $programName = '';
        $programFee = '';

        // Loop through students (although typically we expect one student per mat_number)
        foreach ($studentsWithPrograms as $student) {
            $programName = $student->name; // Accessing name
            $programFee = $student->fee; // Accessing fee
        }


        if ($this->type == 1) {
            $pdf = Pdf::loadView('emails.accepted_application_pdf', [
                'date' => $this->date,
                'matnumber' => $this->matnumber,
                'fullname' => $this->fullname,
                'programName' => $programName,
                'programFee' => $programFee,
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
                'programName' => $programName,
                'programFee' => $programFee,
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
