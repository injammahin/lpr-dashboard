<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserInvitationMail extends Mailable
{
    public $invite;

    public function __construct($invite)
    {
        $this->invite = $invite;
    }

    public function build()
    {
        $url = url('/register/invite/' . $this->invite->token);

        return $this->subject('You are invited!')
            ->view('emails.invite')
            ->with(['url' => $url]);
    }
}
