<?php

namespace Plugin\TlcommerceCore\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderDeliveryStatusLogRepository
{
    private const TABLE = 'tl_com_order_delivery_status_logs';

    private const DISPLAY_TIMEZONE = 'Asia/Kuwait';

    public function tableExists(): bool
    {
        return Schema::hasTable(self::TABLE);
    }

    /**
     * @return array{success: bool}
     */
    public function logStatusChange(int $orderId, int $newStatus, ?int $oldStatus = null): array
    {
        if (!$this->tableExists()) {
            return ['success' => true];
        }

        if ($oldStatus !== null && (int) $oldStatus === (int) $newStatus) {
            return ['success' => true];
        }

        DB::table(self::TABLE)->insert([
            'order_id' => $orderId,
            'delivery_status' => $newStatus,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['success' => true];
    }

    /**
     * Latest timestamp when each order reached its current delivery status.
     *
     * @param array<int, int> $currentStatusByOrderId
     * @return array<int, string>
     */
    public function getCurrentStatusTimestampsForOrders(array $currentStatusByOrderId): array
    {
        if (!$this->tableExists() || empty($currentStatusByOrderId)) {
            return [];
        }

        $orderIds = array_keys($currentStatusByOrderId);

        $rows = DB::table(self::TABLE)
            ->whereIn('order_id', $orderIds)
            ->orderByDesc('id')
            ->get(['order_id', 'delivery_status', 'created_at']);

        $result = [];

        foreach ($rows as $row) {
            $orderId = (int) $row->order_id;

            if (isset($result[$orderId])) {
                continue;
            }

            if ((int) $row->delivery_status === (int) ($currentStatusByOrderId[$orderId] ?? -1)) {
                $result[$orderId] = (string) $row->created_at;
            }
        }

        return $result;
    }

    public function formatTimestampForDisplay(string $datetime): string
    {
        return $this->formatDateForDisplay($datetime) . ' · ' . $this->formatTimeForDisplay($datetime);
    }

    public function formatTimeForDisplay(string $datetime): string
    {
        $parsed = Carbon::parse($datetime)->timezone($this->displayTimezone());
        $timeFormat = ((int) $parsed->format('i')) === 0 ? 'ga' : 'g:ia';

        return strtolower($parsed->format($timeFormat));
    }

    public function formatDateForDisplay(string $datetime): string
    {
        return Carbon::parse($datetime)->timezone($this->displayTimezone())->format('d M, Y');
    }

    public function formatDateKeyForDisplay(string $datetime): string
    {
        return Carbon::parse($datetime)->timezone($this->displayTimezone())->format('Y-m-d');
    }

    private function displayTimezone(): string
    {
        return self::DISPLAY_TIMEZONE;
    }
}
