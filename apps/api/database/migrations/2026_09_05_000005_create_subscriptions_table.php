<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table): void {
            $table->id();
            $table->string('public_id', 26)->unique();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->string('billing_cycle');
            $table->decimal('price', 12, 2);
            $table->string('currency', 3);
            $table->string('status');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('grace_period_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->index('organization_id');
            $table->index('plan_id');
            $table->index('status');
        });

        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT subscriptions_price_non_negative CHECK (price >= 0)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT subscriptions_currency_uppercase CHECK (currency = UPPER(currency))');
        DB::statement("CREATE UNIQUE INDEX subscriptions_one_current_per_organization ON subscriptions (organization_id) WHERE status IN ('pending', 'trialing', 'active', 'grace')");
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
