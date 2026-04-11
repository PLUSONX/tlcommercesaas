<?php

namespace Core\Jobs;

use Core\Mail\TenantMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTenantMailJob
{
    // Removed ShouldQueue interface
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail_data;
    protected $recipientEmail;
    protected $config;

    public function __construct($recipientEmail, $mail_data, $config)
    {
        $this->mail_data = $mail_data;
        $this->recipientEmail = $recipientEmail;
        $this->config = $config;
    }

   public function handle()
    {
        try {
            if (isTenant()) {
                \Log::info("Reconfiguring Mail for Tenant...");
                changeEmailConfiguration();
            }

            $fromEmail = $this->config['mail_from'] ?? env('MAIL_FROM_ADDRESS');
            $fromName = $this->config['mail_from_name'] ?? env('MAIL_FROM_NAME');

            \Log::info("Attempting to send TenantMail to: " . $this->recipientEmail);

            Mail::to($this->recipientEmail)->send(
                new TenantMail($this->mail_data, $fromEmail, $fromName)
            );

            \Log::info("Mail::to successfully completed for: " . $this->recipientEmail);

        } catch (\Exception $e) {
            \Log::error("CRITICAL: SendTenantMailJob Failed", [
                'message' => $e->getMessage(),
                'recipient' => $this->recipientEmail
            ]);
        }
    }
}
// class SendTenantMailJob implements ShouldQueue
// {
//     use Queueable, SerializesModels, InteractsWithQueue, Dispatchable;

//     protected $mail_data;
//     protected $recipientEmail;
//     protected $config;

//     public function __construct($recipientEmail, $mail_data, $config)
//     {
//         $this->mail_data = $mail_data;
//         $this->recipientEmail = $recipientEmail;
//         $this->config = $config;
//     }

//     public function handle()
//     {
//         // Set tenant-specific mail configuration
//         Config::set('mail.mailers.smtp.host', $this->config['host']);
//         Config::set('mail.mailers.smtp.port', $this->config['port']);
//         Config::set('mail.mailers.smtp.username', $this->config['username']);
//         Config::set('mail.mailers.smtp.password', $this->config['password']);
//         Config::set('mail.mailers.smtp.encryption', $this->config['encryption']);
//         Config::set('mail.from.address', $this->config['mail_from']);
//         Config::set('mail.from.name', $this->config['mail_from_name']);

//         // Send mail using tenant's mail config
//         Mail::to($this->recipientEmail)->send(new TenantMail($this->mail_data, $this->config['mail_from'], $this->config['mail_from_name']));
//     }
// }
