<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadReciept extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $name;
    public $id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $id, $file)
    {
        $this->file = $file;
        $this->$name = $name;
        $this->$id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.invoice')
                    ->from('projectempower@honeypays.com.ng', 'HONEYPAYS PROJECT EMPOWER')
                    ->subject('Receipt '.$this->id)
                    ->attachData($this->file,$this->id.'.pdf')
                    ->view('email.invoice');
    }
}
