<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invitation;
use App\Models\CustomSubscription;

class CustomSubscriptionInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;
    public $customSubscription;
    public $activationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Invitation $invitation, CustomSubscription $customSubscription)
    {
        $this->invitation = $invitation;
        $this->customSubscription = $customSubscription;
        $this->activationUrl = route('invitation.accept', ['token' => $invitation->token]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Uitnodiging voor Mijn Loonstrookje - Custom Abonnement',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.custom-subscription-invitation',
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
