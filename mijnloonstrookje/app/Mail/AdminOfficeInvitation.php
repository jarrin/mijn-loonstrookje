<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;

class AdminOfficeInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $employerName;
    public $companyName;
    public $activationUrl;
    public $isNewAccount;

    /**
     * Create a new message instance.
     */
    public function __construct(Invitation $invitation, $employerName, $companyName, $isNewAccount = true)
    {
        $this->invitation = $invitation;
        $this->employerName = $employerName;
        $this->companyName = $companyName;
        $this->isNewAccount = $isNewAccount;
        $this->activationUrl = route('invitation.accept', ['token' => $invitation->token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isNewAccount 
            ? 'Uitnodiging voor Mijn Loonstrookje - Account aanmaken'
            : 'Uitnodiging voor toegang tot ' . $this->companyName;
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->isNewAccount 
            ? 'mails.admin-office-invitation-new'
            : 'mails.admin-office-invitation-existing';
            
        return new Content(
            view: $view,
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
