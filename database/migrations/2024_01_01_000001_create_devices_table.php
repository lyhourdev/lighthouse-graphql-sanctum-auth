<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Devices Table Migration
 * 
 * Creates the devices table for device management.
 * បង្កើតតារាង devices សម្រាប់ការគ្រប់គ្រង device។
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * ប្រតិបត្តិ migrations។
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('token_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index('token_id');
        });
    }

    /**
     * Reverse the migrations.
     * បញ្ច្រាស migrations។
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};

