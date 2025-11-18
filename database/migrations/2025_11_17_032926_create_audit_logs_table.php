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
        if (! Schema::hasTable('audit_logs')) {
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
        } else {
            // Table already exists, just add missing indexes
            Schema::table('audit_logs', function (Blueprint $table): void {
                if (! $this->hasIndex('audit_logs', 'audit_logs_user_id_action_index')) {
                    $table->index(['user_id', 'action']);
                }
                if (! $this->hasIndex('audit_logs', 'audit_logs_auditable_type_auditable_id_index')) {
                    $table->index(['auditable_type', 'auditable_id']);
                }
                if (! $this->hasIndex('audit_logs', 'audit_logs_created_at_index')) {
                    $table->index('created_at');
                }
            });
        }
    }

    /**
     * Check if an index exists on a table.
     */
    private function hasIndex(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        $result = $connection->select(
            'SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?',
            [$database, $table, $index]
        );

        return $result[0]->count > 0;
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
