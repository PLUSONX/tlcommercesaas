<?php

namespace Plugin\Saas\Repositories;

use Carbon\Carbon;
use App\Models\Tenant;
use App\Models\User;
use Plugin\Saas\Models\SaasAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\TenantDatabaseMonitoringJob;

class TenantRepository
{

    /**
     * Generate unique tenant id
     */
    public function generateTenantID()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    /**
     * Create new tenant and database for newly registered user 
     */
    public function createNewTenant($request)
    {
        // Get the database configuration for the tenant's database
        $tenantConfig = config('database.connections.tenant');
        $subdomain = implode('', explode(' ', strtolower($request['store_name'])));
        $database_prefix = \Plugin\Saas\Repositories\SettingsRepository::getSaasSetting('database_prefix');
        if (!empty($database_prefix)) {
            $database = strtolower($database_prefix . '_' . $subdomain);
        } else {
            $database = strtolower($subdomain);
        }

        $tenant_id = $this->generateTenantID();
        DB::table('tenants')->insert([
            'id' => $tenant_id,
            'data' => json_encode([
                'created_at' => currentDateTime(),
                'update_at' => currentDateTime(),
                'tenancy_db_name' => $database
            ])
        ]);

        $tenant = Tenant::find($tenant_id);
        $tenant->domains()->create([
            'domain' => $subdomain . '.' . getCurrentHostName(),
            'main_domain' => $subdomain . '.' . getCurrentHostName()
        ]);
        session()->put('tenant_id', $tenant->id);
        return $tenant;
    }
    /**
     * Register user for newly created store
     */
    public function registerUserForNewStore($query, $saas_account)
    {
        try {
            //Create SUPER ADMIN
            $date = Carbon::now();
            $user_id = $date->format('y') . $date->format('m') . $date->format('d') . $date->format('h') . $date->format('m') . $date->format('s');
            $user_name = $saas_account->user->name;
            $user_email = $saas_account->user->email;
            $user_password = $saas_account->user->password;
            $user_status = config('settings.user_status.active');
            $user_role = config('settings.roles.supper_admin');

            $user_data = [
                'name' => $user_name,
                'email' => $user_email,
                'user_type' => 1,
                'password' => $user_password,
                'status' => $user_status
            ];

            $inserted_id = $query->table('tl_users')->insertGetId($user_data);
            $user_uid = "SUPER-ADMIN-" . $inserted_id . $user_id;
            $query->table('tl_users')->update([
                'uid' => $user_uid
            ]);

            //Assign USER ROLE
            $user_role_data = [
                'role_id' => $user_role,
                'model_type' => 'Core\Models\User',
                'model_id' => $inserted_id
            ];

            $query->table('model_has_roles')->insert($user_role_data);

            //Set System Name
            $settings_data = [
                'settings_id' => getGeneralSettingId('system_name'),
                'value' => $saas_account->store_name
            ];

            $query->table('tl_general_settings_has_values')->where('settings_id', getGeneralSettingId('system_name'))->delete();
            $query->table('tl_general_settings_has_values')->insert($settings_data);

            //Add user to default products
            $query->table('tl_com_products')->update([
                'supplier' => $inserted_id
            ]);

            $query->table('tl_com_ordered_products')->update([
                'seller_id' => $inserted_id
            ]);

            //Set Tenant ID
            $query->table('tl_general_settings_has_values')->insert([
                'settings_id' => 359,
                'value' => $saas_account->tenant_id
            ]);

            //Set Default Language 
            $this->setDefaultLanguage($saas_account, $query);

            //Set Default Currency
            $this->setDefaultCurrency($saas_account, $query);

            $query->commit();
        } catch (\Exception $ex) {
            $error = [
                'message' => 'Error occurred during registering default user for tenant panel',
                'data' => $saas_account,
                'error' => $ex
            ];
            Log::channel('tenant_database')->info(json_encode($error));
            $query->rollback();
        }
    }

    public function setDefaultLanguage($saas_account, $query)
    {

        try {
            if ($saas_account->initial_language == null) {
                return false;
            }

            $language_details = DB::table('tl_languages')->where('id', $saas_account->initial_language)->first();

            $tenant_db_language_details = $query->table('tl_languages')->where('code', $language_details->code)->first();


            $tenant_language_id = "";

            if ($tenant_db_language_details != null) {
                $tenant_language_id = $tenant_db_language_details->id;
            }

            if ($tenant_db_language_details == null) {
                $language_data = [
                    'name' => $language_details->native_name,
                    'native_name' => $language_details->native_name,
                    'code' => 1,
                    'flag' => $language_details->flag,
                    'is_rtl' => $language_details->is_rtl,
                    'status' => 1
                ];
                $tenant_language_id = $query->table('tl_languages')->insertGetId($language_data);
            }


            $language_setting = $query->table('tl_general_settings')->where('name', 'default_language')->first();
            $query->table('tl_general_settings_has_values')->where('settings_id', $language_setting?->id)->update([
                'value' => $tenant_language_id
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function setDefaultCurrency($saas_account, $query)
    {
        try {
            if ($saas_account->initial_currency == null) {
                return false;
            }

            $currency_details = DB::table('tl_saas_currencies')->where('id', $saas_account->initial_currency)->first();

            $tenant_db_currency_details = $query->table('tl_com_currencies')->where('code', $currency_details->code)->first();
            $tenant_currency_id = "";

            if ($tenant_db_currency_details != null) {
                $tenant_currency_id = $tenant_db_currency_details->id;
            }

            if ($tenant_db_currency_details == null) {
                $currency_data = [
                    'name' => $currency_details->name,
                    'code' => $currency_details->code,
                    'symbol' => $currency_details->symbol,
                    'conversion_rate' => $currency_details->conversion_rate,
                    'position' => $currency_details->position,
                    'thousand_separator' => $currency_details->thousand_separator,
                    'decimal_separator' => $currency_details->decimal_separator,
                    'number_of_decimal' => $currency_details->number_of_decimal,
                    'status' => 1
                ];
                $tenant_currency_id = $query->table('tl_com_currencies')->insertGetId($currency_data);
            }


            $query->table('tl_com_ecommerce_settings')->where('key_name', 'default_currency')->update([
                'key_value' => $tenant_currency_id
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * Will insert third party plugin tables
     */
    public function insertThirdPartyPluginTables($query)
    {
        $all_plugins_location = DB::table('tl_plugins')->where('type', '=', 'outside')->pluck('location')->toArray();
        $installed_plugins_locations = $query->table('tl_plugins')->pluck('location')->toArray();
        $uninstalled_plugin_locations = array_diff($all_plugins_location, $installed_plugins_locations);

        foreach ($uninstalled_plugin_locations as $location) {
            $sql_path = base_path('plugins/' . $location . '/data.sql');
            if (file_exists($sql_path)) {
                $sql = file_get_contents($sql_path);
                $query->unprepared($sql);
            }
        }
    }

    /**
     * Update tenant plugin database
     */
    public function updateAllTenantPluginData($query, $package_id)
    {
        try {
            $tlcommerce_plugin_location = DB::table('tl_plugins')->where('location', '=', 'tlecommercecore')->value('location');
            $plugin_locations = DB::table('tl_saas_package_has_plugins')
                ->join('tl_plugins', 'tl_plugins.id', '=', 'tl_saas_package_has_plugins.plugin_id')
                ->where('package_id', '=', $package_id)
                ->pluck('tl_plugins.location')
                ->toArray();

            array_push($plugin_locations, $tlcommerce_plugin_location);

            if (!empty($plugin_locations)) {
                $query->table('tl_plugins')
                    ->whereIn('location', $plugin_locations)
                    ->update(['is_activated' => 1]);

                $query->table('tl_plugins')
                    ->whereNotIn('location', $plugin_locations)
                    ->update(['is_activated' => 0]);
            } else {
                $query->table('tl_plugins')->update(['is_activated' => 0]);
            }
        } catch (\Exception $ex) {
            $error = [
                'message' => 'Error occurred during updating tenant plugin data',
                'data' => $query,
                'error' => $ex
            ];
            Log::channel('tenant_database')->info(json_encode($error));

            $query->rollback();
        }
    }

    /**
     * Update tenant payment method data
     */
    public function updateAllTenantPaymentMethodData($query, $package_id)
    {
        try {
            $permitted_payment_methods = DB::table('tl_saas_package_has_payment_methods')->where('package_id', '=', $package_id)->pluck('payment_method_id');
            $available_payment_methods = DB::table('tl_com_payment_methods')->whereIn('id', $permitted_payment_methods)->select('id', 'name')->get();
            if (sizeof($permitted_payment_methods) > 0) {

                //Remove payment method
                $removable_methods = [];
                $exiting_payment_method = $query->table('tl_com_payment_methods')->pluck('id');
                foreach ($exiting_payment_method as $item) {
                    if (!in_array($item, $permitted_payment_methods->toArray())) {
                        array_push($removable_methods, $item);
                    }
                }

                if (sizeof($removable_methods) > 0) {
                    $query->statement('SET FOREIGN_KEY_CHECKS=0;');
                    $query->table('tl_com_payment_methods')->whereIn('id', $removable_methods)->delete();
                    $query->statement('SET FOREIGN_KEY_CHECKS=1;');
                }

                //Insert New Payment Method
                foreach ($available_payment_methods as $key => $apm) {
                    $payment_method = $query->table('tl_com_payment_methods')->where('id', $apm->id)->select('id', 'name', 'status')->first();
                    if ($payment_method == null) {
                        $query->table('tl_com_payment_methods')->insert([
                            'id' => $apm->id,
                            'name' => $apm->name,
                            'status' => 2,
                        ]);
                    }
                }
            } else {
                $query->table('tl_com_payment_methods')->delete();
            }
        } catch (\Exception $ex) {
            $error = [
                'message' => 'Error occurred during updating tenant payment method data',
                'data' => $query,
                'error' => $ex
            ];
            Log::channel('tenant_database')->info(json_encode($error));

            $query->rollback();
        }
    }

    /**
     * Will update tenant database
     */
    public function updateTenantDatabase($package_id)
    {
        $all_saas_account = DB::table('tl_saas_packages')
            ->join('tl_saas_accounts', 'tl_saas_accounts.package_id', '=', 'tl_saas_packages.id')
            ->where('tl_saas_packages.id', '=', $package_id)
            ->where('tl_saas_accounts.is_db_created', '=', 1);

        $all_saas_account->update([
            'is_db_updated' => 0
        ]);

        $all_saas_account = $all_saas_account->pluck('tl_saas_accounts.id as saas_account_id');

        for ($i = 0; $i < sizeof($all_saas_account); $i++) {
            $job = new TenantDatabaseMonitoringJob($all_saas_account[$i], $package_id, 1);
            dispatch($job);
        }
    }

    /**
     * Will update single tenant database
     */
    public function createOrUpdateSingleTenantDatabase($tenant_id, $package_id, $saas_account_id, $is_for_update, $is_from_admin = false)
    {
        if ((systemHasPermissionToCreateDatabase() && $is_for_update == 0) || ($is_for_update == 0 && $is_from_admin)) {
            $tenant = Tenant::find($tenant_id);
            $database = $tenant->tenancy_db_name;
            $tenantConfig = config('database.connections.tenant');
            $tenantConfig['database'] = $database;
            $tenantConfig['log'] = true;
            $tenantConnectionName = 'tenant_' . $database;
            config(["database.connections.$tenantConnectionName" => $tenantConfig]);
            session()->put('tenant_connection_name', $tenantConnectionName);

            $query = DB::connection($tenantConnectionName);

            if (systemHasPermissionToCreateDatabase()) {
                $sql = 'CREATE DATABASE IF NOT EXISTS ' . $database . ';';
                DB::unprepared($sql);
            }


            $this->refreshTenantDatabase($query, $database);
            $sqlFilePath = storage_path('app/tenant.sql');
            // $sqlFilePath = base_path('database/saas.sql');
            $sqlContent = file_get_contents($sqlFilePath);

            $saasAccount = DB::table('tl_saas_accounts')->where('id', $saas_account_id)->first();

            $subscriber = User::find($saasAccount->user_id);

            // // Replace placeholder with actual user data
            $dynamicUserInsert = $this->generateUserInsert($subscriber, $tenant);


            $sqlContent = str_replace('{{DYNAMIC_USER_INSERT}}', $dynamicUserInsert, $sqlContent);


            // $query->unprepared($sqlContent);         ---Hassaan Commented
            $this->runSqlFile($sqlContent, $query);

            $job = new TenantDatabaseMonitoringJob($saas_account_id, $package_id, $is_for_update);
            dispatch($job);
        }
        if ($is_for_update == 1) {
            $job = new TenantDatabaseMonitoringJob($saas_account_id, $package_id, $is_for_update);
            dispatch($job);
        }
    }
//// Hassaan created these two below function
    protected function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $length = strlen($sql);
        $inSingleQuote = false;
        $inDoubleQuote = false;
        $inLineComment = false;
        $inBlockComment = false;

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];
            $prevChar = $i > 0 ? $sql[$i - 1] : '';
            $nextChar = $i < $length - 1 ? $sql[$i + 1] : '';

            // End of line comment
            if ($inLineComment) {
                $current .= $char;
                if ($char === "\n") {
                    $inLineComment = false;
                }
                continue;
            }

            // End of block comment
            if ($inBlockComment) {
                $current .= $char;
                if ($prevChar === '*' && $char === '/') {
                    $inBlockComment = false;
                }
                continue;
            }

            // Detect start of comments (only when not inside a string)
            if (!$inSingleQuote && !$inDoubleQuote) {
                if (($char === '-' && $nextChar === '-') || $char === '#') {
                    $inLineComment = true;
                    $current .= $char;
                    continue;
                }
                if ($char === '/' && $nextChar === '*') {
                    $inBlockComment = true;
                    $current .= $char;
                    continue;
                }
            }

            // Track quote state; only treat quote as delimiter when not escaped by odd backslashes
            if (($char === "'" && !$inDoubleQuote) || ($char === '"' && !$inSingleQuote)) {
                $backslashes = 0;
                for ($j = $i - 1; $j >= 0 && $sql[$j] === '\\'; $j--) {
                    $backslashes++;
                }

                if ($backslashes % 2 === 0) {
                    if ($char === "'") {
                        $inSingleQuote = !$inSingleQuote;
                    } else {
                        $inDoubleQuote = !$inDoubleQuote;
                    }
                }
            }

            $current .= $char;

            if ($char === ';' && !$inSingleQuote && !$inDoubleQuote) {
                $statements[] = trim($current);
                $current = '';
            }
        }

        if (trim($current) !== '') {
            $statements[] = trim($current);
        }

        return $statements;
    }

    protected function runSqlFile(string $sqlContent, $connection)
    {
        // No more manual line-based comment stripping needed —
        // the splitter now handles comments itself. Pass raw content directly.
        $statements = $this->splitSqlStatements($sqlContent);

        foreach ($statements as $i => $statement) {
            $trimmed = trim($statement);
            if ($trimmed === '' || $trimmed === ';') continue;
            try {
                $connection->unprepared($statement);
            } catch (\Throwable $e) {
                $logPath = storage_path('app/tenant-sql-error.log');
                file_put_contents(
                    $logPath,
                    "Statement #$i failed\n\n" .
                        "ERROR:\n" . $e->getMessage() . "\n\n" .
                        "FULL STATEMENT:\n" . $statement . "\n"
                );
                dd("Failed at statement #$i — see storage/app/tenant-sql-error.log for full details");
            }
        }
    }

    private function generateUserInsert($subscriber, $tenant)
    {

        // if (!$subscriber) {
        //     \Log::channel('tenant_database')->error('Subscriber is null', ['tenant_id' => $tenant->id]);
        //     throw new \Exception('Subscriber not found');
        // }

        // \Log::channel('tenant_database')->info('Generating user insert', [
        //     'subscriber_id' => $subscriber->id,
        //     'subscriber_name' => $subscriber->name,
        //     'subscriber_email' => $subscriber->email,
        //     'tenant_id' => $tenant->id,
        // ]);

        // Use DB::getPdo()->quote() for proper escaping
        $pdo = DB::connection()->getPdo();

        $uid = 'Subscriber-ADMIN-' . $tenant->id;

        // PDO::quote() adds quotes AND escapes properly
        $name = $pdo->quote($subscriber->name);
        $email = $pdo->quote($subscriber->email);
        $password = $pdo->quote($subscriber->password);
        $createdAt = $pdo->quote(now()->format('Y-m-d H:i:s'));

        // Note: PDO::quote() includes the quotes, so don't add extra ones
        $sql = "INSERT INTO `tl_users` (`id`, `uid`, `name`, `email`, `image`, `password`, `remember_token`, `email_verified_at`, `is_logged_in`, `status`, `user_type`, `created_at`, `updated_at`) 
        VALUES (1, '{$uid}', {$name}, {$email}, NULL, {$password}, NULL, NULL, NULL, 1, 1, {$createdAt}, {$createdAt});";

        // echo "<script>console.log('sql:', " . json_encode($sql) . ");</script>";

        // \Log::channel('tenant_database')->info('Generated SQL', ['sql' => $sql]);

        return $sql;
    }

//     private function generateUserInsert($subscriber, $tenant)
// {
//     // if (!$subscriber) {
//     //     \Log::error('Subscriber is null', ['tenant_id' => $tenant->id]);
//     //     throw new \Exception('Subscriber not found');
//     // }

//     // Properly escape all values
//     try {
//             $uid = 'Subscriber-ADMIN-' . $tenant->id;
//     $name = addslashes($subscriber->name);
//     $email = addslashes($subscriber->email);
//     $password = $subscriber->password; // Already hashed
//     $createdAt = now()->format('Y-m-d H:i:s');
    
//     // All string values must be quoted consistently
//     return "INSERT INTO `tl_users` (`id`, `uid`, `name`, `email`, `image`, `password`, `remember_token`, `email_verified_at`, `is_logged_in`, `status`, `user_type`, `created_at`, `updated_at`) 
//     VALUES (1, '{$uid}', '{$name}', '{$email}', NULL, '{$password}', NULL, NULL, NULL, 1, 1, '{$createdAt}', '{$createdAt}');";
//     }
//     catch(Exception $ex) {
//          $error = [
//                 'error' => $ex
//             ];
//             Log::channel('tenant_database')->info(json_encode($error));

//             // toastNotification('error', translate('Tenant database creation unsuccessful!'));
//             // return back();
//     } 

// }

//     private function generateUserInsert($subscriber, $tenant)
// {
//     $uid = 'Subscriber-ADMIN-' . $tenant->id;
//     $name = $subscriber->name;
//     $email = $subscriber->email;
//     $password = $subscriber->password; // Already hashed from main database
//     $createdAt = now()->format('Y-m-d H:i:s');
    
//     return "INSERT INTO `tl_users` (`id`, `uid`, `name`, `email`, `image`, `password`, `remember_token`, `email_verified_at`, `is_logged_in`, `status`, `user_type`, `created_at`, `updated_at`) VALUES
//     (1, '{$uid}', " . DB::connection()->getPdo()->quote($name) . ", '{$email}', NULL, '{$password}', NULL, NULL, NULL, 1, 1, '{$createdAt}', '{$createdAt}');";
// }

    /**
     * Drop table if exists
     */
    public function refreshTenantDatabase($query, $database)
    {
        // Disable foreign key checks temporarily
        $query->statement('SET FOREIGN_KEY_CHECKS=0');

        // Get the list of tables in the database
        $tables = $query->select("SHOW TABLES FROM {$database}");

        foreach ($tables as $table) {
            $table = reset($table); // Extract the table name from the result

            // Drop each table
            $query->statement("DROP TABLE IF EXISTS `{$table}`");
        }

        // Enable foreign key checks again
        $query->statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
