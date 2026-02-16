<?php

namespace Core\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Core\Mail\TestEmail;
use Illuminate\Http\Request;
use Core\Models\EmailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Core\Http\Requests\TestEmailRequest;

class EmailController extends Controller
{
    /**
     * Will redirect smtp configuration page
     *
     * @return mixed
     */
    public function smtpConfiguration()
    {
        $data = [
            'mail_driver' => getGeneralSetting('mail_driver'),
            'mailgun_domain' => getGeneralSetting('mailgun_domain'),
            'mailgun_secret' => getGeneralSetting('mailgun_secret'),
            'mail_host' => getGeneralSetting('mail_host'),
            'mail_port' => getGeneralSetting('mail_port'),
            'mail_user_name' => getGeneralSetting('mail_user_name'),
            'mail_password' => getGeneralSetting('mail_password'),
            'mail_encryption' => getGeneralSetting('mail_encryption'),
            'mail_from' => getGeneralSetting('mail_from'),
            'mail_from_name' => getGeneralSetting('mail_from_name')
        ];
        return view('core::base.email.smtp.smtp_config', $data);
    }
    /**
     * Will update smtp configuration
     *
     * @param SmtpRequest $request
     * @return mixed
     */
    public function updateSmtpConfiguration(\Core\Http\Requests\SmtpRequest $request)
    {
        try {
            if (!isTenant()) {
                setEnv('MAIL_MAILER', $request['mail_driver']);
                if ($request['mail_driver'] == 'mailgun') {
                    setEnv('MAILGUN_DOMAIN', $request['mailgun_domain']);
                    setEnv('MAILGUN_SECRET', $request['mailgun_secret']);
                    setEnv('MAIL_HOST', $request['mail_host']);
                    setEnv('MAIL_PORT', $request['mail_port']);
                    setEnv('MAIL_USERNAME', $request['mail_user_name']);
                    setEnv('MAIL_PASSWORD', $request['mail_password']);
                    setEnv('MAIL_ENCRYPTION', $request['mail_encryption']);
                    setEnv('MAIL_FROM_ADDRESS', $request['mail_from']);
                    setEnv('MAIL_FROM_NAME', $request['mail_from_name']);
                } else {
                    setEnv('MAIL_HOST', $request['mail_host']);
                    setEnv('MAIL_PORT', $request['mail_port']);
                    setEnv('MAIL_USERNAME', $request['mail_user_name']);
                    setEnv('MAIL_PASSWORD', $request['mail_password']);
                    setEnv('MAIL_ENCRYPTION', $request['mail_encryption']);
                    setEnv('MAIL_FROM_ADDRESS', $request['mail_from']);
                    setEnv('MAIL_FROM_NAME', $request['mail_from_name']);
                }
            }
            setGeneralSetting('mail_driver', $request['mail_driver']);
            if ($request['mail_driver'] == 'mailgun') {
                setGeneralSetting('mailgun_domain', $request['mailgun_domain']);
                setGeneralSetting('mailgun_secret', $request['mailgun_secret']);
                setGeneralSetting('mail_host', $request['mail_host']);
                setGeneralSetting('mail_port', $request['mail_port']);
                setGeneralSetting('mail_user_name', $request['mail_user_name']);
                setGeneralSetting('mail_password', $request['mail_password']);
                setGeneralSetting('mail_encryption', $request['mail_encryption']);
                setGeneralSetting('mail_from', $request['mail_from']);
                setGeneralSetting('mail_from_name', $request['mail_from_name']);
            } else {
                setGeneralSetting('mail_host', $request['mail_host']);
                setGeneralSetting('mail_port', $request['mail_port']);
                setGeneralSetting('mail_user_name', $request['mail_user_name']);
                setGeneralSetting('mail_password', $request['mail_password']);
                setGeneralSetting('mail_encryption', $request['mail_encryption']);
                setGeneralSetting('mail_from', $request['mail_from']);
                setGeneralSetting('mail_from_name', $request['mail_from_name']);
            }
            toastNotification('success', translate('SMTP configuration updated successfully'));
            return redirect()->route('core.email.smtp.configuration');
        } catch (\Exception $e) {
            toastNotification('error', translate('Update failed'));
            return redirect()->back();
        } catch (\Error $e) {
            toastNotification('error', translate('Update failed'));
            return redirect()->back();
        }
    }


    /**
     * This function must update the runtime configuration
     * so the Mailer knows which credentials to use.
     */
    public function changeEmailConfiguration()
    {
        config([
            'mail.default' => getGeneralSetting('mail_driver') ?? 'smtp',
            'mail.mailers.smtp.host' => getGeneralSetting('mail_host'),
            'mail.mailers.smtp.port' => getGeneralSetting('mail_port'),
            'mail.mailers.smtp.encryption' => getGeneralSetting('mail_encryption'),
            'mail.mailers.smtp.username' => getGeneralSetting('mail_user_name'),
            'mail.mailers.smtp.password' => getGeneralSetting('mail_password'),
            'mail.from.address' => getGeneralSetting('mail_from'),
            'mail.from.name' => getGeneralSetting('mail_from_name'),
        ]);
    }

    /**
     * Will send test email
     *
     * @param TestEmailRequest $request
     * @return mixed
     */
     public function sendTestMail(TestEmailRequest $request)
    {
        Log::info('=== sendTestMail START ===');

        try {

            Log::info('Step 1: Validated request received', [
                'email' => $request->email,
                'subject_length' => strlen($request->subject ?? ''),
                'message_length' => strlen($request->message ?? '')
            ]);

            $mailData = [
                "message" => xss_clean($request['message']),
                "subject" => xss_clean($request['subject'])
            ];

            Log::info('Step 2: Mail data prepared');

            if (isTenant()) {
                Log::info('Step 3: Tenant detected, changing email configuration');

                $this->changeEmailConfiguration();

                Log::info('Step 3.1: Config Verify', ['host' => config('mail.mailers.smtp.host')]);

                Log::info('Step 3.2: Purging Mailer Instance');

                Log::info('Step 4: Clearing mailers cache');
                
                app()->make(\Illuminate\Mail\MailManager::class)->forgetMailers();
            } else {
                Log::info('Step 3: Not a tenant');
            }

            Log::info('Step 4.5: Current SMTP Host', ['host' => config('mail.mailers.smtp.host')]);

            Log::info('Step 4.5.1: Current SMTP Host', ['host' => getGeneralSetting('mail_host')]);

            Log::info('Step 4.5.2: Current SMTP Post', ['port' => getGeneralSetting('mail_port')]);

            Log::info('Step 5: Attempting to send email', [
                'to' => $request->email
            ]);

            Mail::to($request['email'])->send(new TestEmail($mailData));

            Log::info('Step 6: Email sent successfully');

            toastNotification('success', translate('A new email sending successfully'));

            Log::info('=== sendTestMail END SUCCESS ===');

            return redirect()->route('core.email.smtp.configuration');

        } catch (\Throwable $e) {

            Log::error('=== sendTestMail FAILED ===', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            toastNotification('error', translate('Email sending failed'));

            return redirect()->back();
        }
    }
    // public function sendTestMail(TestEmailRequest $request)
    // {
    //     try {
    //         $mailData = [
    //             "message" => xss_clean($request['message']),
    //             "subject" => xss_clean($request['subject'])
    //         ];

    //         if (isTenant()) {
    //             changeEmailConfiguration();
    //             app()->make(\Illuminate\Mail\MailManager::class)->forgetMailers();
    //         }
    //         Mail::to($request['email'])->send(new TestEmail($mailData));
    //         toastNotification('success', translate('A new email sending successfully'));
    //         return redirect()->route('core.email.smtp.configuration');
    //     } catch (\Exception $e) {
    //         toastNotification('error', translate('Email sending failed'));
    //         return redirect()->back();
    //     }
    // }
    /**
     * will redirect to email template listings
     *
     * @return void
     */
    public function emailTemplates()
    {
        return view('core::base.email.email_templates.index');
    }

    /**
     * update email template
     *
     * @param  Request $request
     * @return mixed
     */
    public function updateEmailTemplate(Request $request)
    {
        $validation = $this->validate($request, [
            'template_id' => 'required|exists:tl_email_template_properties,email_type',
            'subject' => 'required',
            'content' => 'required',
        ]);

        try {
            $template_content = EmailTemplate::where('email_type', $request['template_id'])->first();
            $template_content->subject = xss_clean($request['subject']);
            $template_content->body = $request['content'];
            $template_content->save();

            return response()->json([
                'success' => true,
                'message' => translate("Email template updated successful")
            ]);
        } catch (\Exception $th) {
            return response()->json([
                'success' => false,
                'message' => translate("Unable to update email template")
            ], 500);
        }
    }

    /**
     * Get email template variables
     */
    public function getEmailTemplateVariables(Request $request)
    {
        $variables = getEmailTemplateVariables($request['template_id']);
        return view('core::base.email.email_templates.email_template_variables', compact('variables'));
    }
}
