<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── ORDERS: pastikan provider_id NOT NULL (FK sudah cascade) ──
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `orders` MODIFY `provider_id` BIGINT UNSIGNED NOT NULL');

        // ── REVIEWS: ikat review ke jasa ─────────────────────
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('provider_id')
                ->constrained('services')->nullOnDelete();
            $table->unique('order_id'); // 1 review per pesanan
        });

        // ── PAYMENTS: jejak pembayaran ───────────────────────
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('method', 50)->default('transfer'); // transfer / cod / ewallet
            $table->string('status', 30)->default('pending'); // pending / paid / failed / refunded
            $table->unsignedInteger('amount');
            $table->string('proof')->nullable(); // bukti bayar
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });

        // ── FAVORITES: jasa tersimpan pelanggan ──────────────
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['customer_id', 'service_id']);
        });

        // ── PROVIDER PROFILES: data usaha mitra ──────────────
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->string('operating_hours', 100)->nullable(); // cth: 08.00 - 22.00
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account', 100)->nullable();
            $table->timestamps();
        });

        // ── NOTIFICATIONS (skema standar Laravel) ────────────
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('provider_profiles');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('payments');

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropUnique(['order_id']);
            $table->dropColumn('service_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropForeign(['option_id']);
            $table->dropColumn(['order_code', 'service_id', 'option_id', 'scheduled_at', 'address']);
        });
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE `orders` MODIFY `provider_id` BIGINT UNSIGNED NULL');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['provider_id']);
            $table->foreign('provider_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['avg_rating', 'review_count']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'icon']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'address', 'bio']);
        });
    }
};
