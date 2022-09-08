<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompStatusEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
    $this->data= $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   $address = env('MAIL_FROM_ADDRESS');
        $subject = $this->data['subject'];
        $name = env('APP_NAME');
       
        return $this->view('emails.company_status')
        ->from($address, $name)
        ->replyTo($address, $name)
        ->subject($subject)
        ->with([ 'name' => $this->data['name'], 'status' => $this->data['status']]);
      
    }
}
