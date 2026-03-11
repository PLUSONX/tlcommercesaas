<?php

namespace Plugin\Saas\Http\Middleware;

use Illuminate\Support\Facades\Log;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Plugin\Saas\Models\SaasAccount;

class HandleSubsExpiredAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
{
    try {
        SaasAccount::on('mysql')
            ->whereNotNull('valid_till')
            ->where('valid_till', '<', now()->format('Y-m-d'))
            ->where('status', '!=', 0)
            ->update(['status' => 0]);
    } catch (\Exception $e) {
        \Log::error('HandleSubsExpiredAccount failed: ' . $e->getMessage());
    }

    return $next($request);
}
    // public function handle(Request $request, Closure $next)
    // {
    //     $saas_accounts = SaasAccount::on('mysql')->get(); // 'mysql' = your main DB connection name

    //     foreach ($saas_accounts as $account) {
    //         if ($account->valid_till != null) {
    //             $saas_account = SaasAccount::on('mysql')->find((int)$account->id);

    //             $valid_till = $account->valid_till;
    //             $currentDate = (new DateTime())->format('Y-m-d');
    //             $expired_date = (new DateTime($valid_till))->format('Y-m-d');

    //             if ($expired_date < $currentDate) {
    //                 $saas_account->status = 0;
    //                 $saas_account->save();
    //             }
    //         }
    //     }

    //     Log::info('HandleSubsExpiredAccount request:', [
    //             'request' => json_encode($request),
    //         ]);

    //     return $next($request);
    // }
    // public function handle(Request $request, Closure $next)
    // {
    //     $saas_accounts = SaasAccount::all();

    //     foreach ($saas_accounts as $account) {
    //         if ($account->valid_till != null) {
    //             $saas_account = SaasAccount::find((int)$account->id);

    //             $valid_till = $account->valid_till;

    //             $currentDate = (new DateTime())->format('Y-m-d'); // creates a DateTime object representing the current date and time
    //             $expired_date = (new DateTime($valid_till))->format('Y-m-d');; // creates a DateTime object from the date string

    //             if ($expired_date < $currentDate) {
    //                 $saas_account->status = 0;
    //                 $saas_account->update();
    //             }
    //         }
    //     }

    //     return $next($request);
    // }
}
