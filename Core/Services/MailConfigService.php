<?php

namespace Core\Services;

use Illuminate\Mail\MailManager;

class MailConfigService
{
    public static function applyTenantMailConfig()
    {
        if (isTenant()) {

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

            app(MailManager::class)->forgetMailers();
        }
    }
}
