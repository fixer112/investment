<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Dues extends Mailable
{
    use Queueable, SerializesModels;

    
    //public $content;
    public $year;
    public $month;
    public $name;

    /**
     * Create a new content instance.
     *
     * @return void
     */
    public function __construct($month, $year, $name)
    {
        //$this->content = $content;
        $this->year = $year;
        $this->month = $month;
        $this->name = $name;
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
                    ->subject("Dues for ".$this->month."/".$this->year)
                    ->attach(storage_path('excel/dues/').$this->name.".xls", [
                            'as' => $this->name.".xls", 
                            'mime' => 'application/vnd.ms-excel'
                        ])
                    ->view('email.Dues');
    }
}
