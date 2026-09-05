<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 12, 2)->default(0);
            $table->decimal('price_yearly', 12, 2)->nullable();
            $table->string('currency', 3)->default('XOF');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::statement('ALTER TABLE plans ADD CONSTRAINT plans_price_monthly_non_negative CHECK (price_monthly >= 0)');
        DB::statement('ALTER TABLE plans ADD CONSTRAINT plans_price_yearly_non_negative CHECK (price_yearly IS NULL OR price_yearly >= 0)');
        DB::statement('ALTER TABLE plans ADD CONSTRAINT plans_currency_uppercase CHECK (currency = UPPER(currency))');
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
