<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;

class EmployeeInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $employerName;
    public $companyName;
    public $activationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Invitation $invitation, $employerName, $companyName)
    {
        $this->invitation = $invitation;
        $this->employerName = $employerName;
        $this->companyName = $companyName;
        $this->activationUrl = route('invitation.accept', ['token' => $invitation->token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Uitnodiging voor Mijn Loonstrookje',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.employee-invitation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
