<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Reciept extends Mailable
{
    use Queueable, SerializesModels;

    
    public $content;
    public $name;
    public $id;

    /**
     * Create a new content instance.
     *
     * @return void
     */
    public function __construct($content, $name = null, $id = null)
    {
        $this->content = $content;
        $this->name = $name;
        $this->id = $id;
        //echo $this->id;
    }

    /**
     * Build the content.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('projectempower@honeypays.com.ng', 'HONEYPAYS PROJECT EMPOWER')
                    ->subject('Reciept '.$this->id)
                    ->attachData($this->content,'Invoice-'.$this->id.'.pdf')
                    ->view('email.invoice');
    }
}
