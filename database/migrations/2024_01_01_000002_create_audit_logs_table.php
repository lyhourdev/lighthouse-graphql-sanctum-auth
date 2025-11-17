<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Create Audit Logs Table Migration
 * 
 * Creates the audit_logs table for audit logging.
 * បង្កើតតារាង audit_logs សម្រាប់ audit logging។
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     * ប្រតិបត្តិ migrations។
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->nullableMorphs('auditable');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('data')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     * បញ្ច្រាស migrations។
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

