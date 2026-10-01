<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InstallStudioBooking extends Command
{
    protected $signature = 'studio-booking:install {--tenant= : Tenant ID to install into} {--all : Install into every tenant database}';
    protected $description = 'Create the tenant-only Studio Booking tables without touching central tables.';

    public function handle(): int
    {
        if (!$this->option('tenant') && !$this->option('all')) {
            $this->error('Choose --tenant=TENANT_ID or explicitly use --all.');
            return self::FAILURE;
        }

        $tenants = $this->option('all')
            ? Tenant::query()->get()
            : Tenant::query()->whereKey($this->option('tenant'))->get();

        if ($tenants->isEmpty()) {
            $this->error('No matching tenant found.');
            return self::FAILURE;
        }

        foreach ($tenants as $tenant) {
            $this->line('Installing Studio Booking for tenant: ' . $tenant->id);
            tenancy()->initialize($tenant);

            try {
                $this->createTables();
                $this->info('  OK');
            } finally {
                tenancy()->end();
            }
        }

        return self::SUCCESS;
    }

    private function createTables(): void
    {
        if (!Schema::hasTable('tl_com_studio_booking_slots')) {
            Schema::create('tl_com_studio_booking_slots', function (Blueprint $table) {
                $table->id();
                $table->date('schedule_date');
                $table->time('start_time');
                $table->time('end_time');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
                $table->unique(['schedule_date', 'start_time', 'end_time'], 'studio_booking_slots_date_time_unique');
                $table->index(['schedule_date', 'status'], 'studio_booking_slots_date_status_idx');
            });
        }

        if (!Schema::hasTable('tl_com_studio_bookings')) {
            Schema::create('tl_com_studio_bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_token', 64)->unique();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->date('schedule_date');
                $table->time('start_time');
                $table->time('end_time');
                $table->unsignedInteger('duration_minutes');
                $table->string('status', 20)->default('held');
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
                $table->index(['schedule_date', 'status', 'start_time', 'end_time'], 'studio_bookings_date_status_time_idx');
                $table->index('order_id', 'studio_bookings_order_idx');
                $table->index('customer_id', 'studio_bookings_customer_idx');
            });
        }
    }
}
