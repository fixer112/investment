<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Honeypays extends Mailable
{
    use Queueable, SerializesModels;

    
    public $content;
    public $logo;
    public $subject;
    public $link;

    /**
     * Create a new content instance.
     *
     * @return void
     */
    public function __construct($content, $subject = null, $link = null)
    {
        $this->content = $content;
        $this->logo = '/honeylogo.jpg';
        $this->subject = $subject;
        $this->link = $link;
    }

    /**
     * Build the content.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('projectempower@honeypays.com.ng', 'HONEYPAYS PROJECT EMPOWER')
                    ->subject($this->subject)
                    ->view('email.honeypays');
    }
}
