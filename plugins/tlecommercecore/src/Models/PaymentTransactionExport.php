<?php

namespace Plugin\TlcommerceCore\Models;

use Plugin\TlcommerceCore\Models\PaymentTransaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PaymentTransactionExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

   public function query()
{
    $query = PaymentTransaction::query();

    // Filter by Payment Method
    if ($this->request->has('payment_method') && $this->request->payment_method != null) {
        $query->where('payment_method', $this->request->payment_method);
    }

    // Filter by Date Range
    if ($this->request->has('export_date_range') && !empty($this->request->export_date_range)) {
        // Explode the range (usually separated by " - " in the picker)
        $date_range = explode(' - ', $this->request->export_date_range);

        if (count($date_range) === 2) {
            try {
                // Parse the strings into Carbon objects using the format from your JS
                $start = Carbon::createFromFormat('m/d/Y', trim($date_range[0]))->startOfDay();
                $end   = Carbon::createFromFormat('m/d/Y', trim($date_range[1]))->endOfDay();

                $query->whereBetween('created_at', [$start, $end]);
            } catch (\Exception $e) {
                \Log::error('Date parsing failed in Export:', ['error' => $e->getMessage()]);
            }
        }
    }

    return $query->orderBy('id', 'DESC');
}

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Payment Method',
            'Payment For',
            'Customer Name',
            'Amount',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->created_at->format('d-M-Y H:i'),
            $transaction->payment_method,
            $transaction->payment_for,
            $transaction->customer_info ? $transaction->customer_info->name : 'Guest',
            $transaction->paid_amount,
            $transaction->status == 1 ? 'Success' : 'Pending'
        ];
    }
}