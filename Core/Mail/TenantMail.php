<?php

namespace Core\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// class TenantMail extends Mailable implements ShouldQueue
class TenantMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $fromEmail; // <--- THIS WAS MISSING
    public $fromName;  // <--- ADD THIS TOO JUST IN CASE
    // protected $form_name;
    // protected $form_email;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData, $fromEmail, $fromName)
    {
        $this->mailData = $mailData;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }
    // public function __construct($mailData, $fromEmail, $fromName, $form_email, $form_name)
    // {
    //     $this->mailData = $mailData;
    //     $this->fromEmail = $fromEmail;
    //     $this->fromName = $fromName;
    //     $this->form_email = $form_email;
    //     $this->form_name = $form_name;
    // }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // IMPORTANT: Even if the Job calls it, 
        // calling it here again ensures the Mailer is ready.
        if (isTenant()) {
            changeEmailConfiguration();
        }

        return $this->subject($this->mailData['subject'])
            ->view('core::base.email.email_templates.global_mail_template', [
                'template_id' => $this->mailData['template_id'], 
                'data' => $this->mailData, 
                'keywords' => $this->mailData['keywords']
            ])
            ->from($this->fromEmail, $this->fromName);
    }
    // public function build()
    // {
    //     return $this->subject($this->mailData['subject'])->view('core::base.email.email_templates.global_mail_template', [
    //         'template_id' => $this->mailData['template_id'],
    //         'data' => $this->mailData,
    //         'keywords' => $this->mailData['keywords']
    //     ])->from($this->form_email, $this->form_name);
    // }
}
