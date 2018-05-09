<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Contact extends Mailable
{
    use Queueable, SerializesModels;

    public $content;
    public $from;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from, $content, $subject)
    {
        $this->content = $content;
        $this->email = $from;

        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email, 'HONEYPAYS PROJECT EMPOWER')
                    ->subject($this->subject)
                    ->view('email.honeypays');
    }
}
