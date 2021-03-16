<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailCumpleanios extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $nacimiento;
    public $now;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $nac, $now)
    {
        $this->data = $data;
        $this->nacimiento = $nac;
        $this->now = $now;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Feliz cumpleaños!')->view('layouts.email');
    }
}
